<?php

namespace App\Console\Commands;

use App\Models\Card;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class ImportOracleFile extends Command
{
    protected $signature = 'libvis:import-latest-oracle';

    protected $description = 'Import latest card list from bulk-data json file';

    protected string $oracleDirectory = './storage/app/bulk-data';

    protected array $skippableCardLayouts = [
        'art_series',
        'double_faced_token'
    ];

    public function handle()
    {
        $this->info('Fetching latest oracle file from bulk-data directory.');
        try {
            $oracleData = $this->getOracleData();
            if(empty($oracleData)) {
                throw new exception('Oracle File data could not be loaded or returned as empty file.');
            }

            $bar = $this->output->createProgressBar(count($oracleData));

            $skippedCards = 0;
            foreach($oracleData as $cardData) {
                if (in_array($cardData['layout'], $this->skippableCardLayouts)) {
                    $skippedCards++;
                    $bar->advance();
                    continue;
                }

                $cardData['type'] = $cardData['type_line'];

                if (isset($cardData['card_faces'])){
                    $this->createCardFromFace($cardData);
                    $bar->advance();
                    continue;
                }

                $this->createCard($cardData);

                $bar->advance();
            }

            $bar->finish();

            $this->newLine()->info('Import successful, skipped '.$skippedCards.' cards.');
        } catch (Exception $e) {
            $this->newLine()->warn($e);
        }
    }

    protected function createCard($cardData)
    {
        $cardData['uuid'] = $cardData['id'];
        Card::updateOrCreate(
            ['name' => $cardData['name']],
            [...$cardData]
        );
    }

    protected function createCardFromFace($cardData)
    {
        collect($cardData['card_faces'])->each(function($face) use ($cardData) {
            $face = (object) $face;
            $name = $face->name . ' (' . $cardData['name'] . ')';
            $type = $face->type_line ?? $cardData['type'];

            $this->createCard([
                    'id'            => $cardData['id'],
                    'released_at'   => $cardData['released_at'],
                    'set_name'      => $cardData['set_name'],
                    'legalities'    => $cardData['legalities'],
                    'layout'        => $cardData['layout'],
                    'name'          => $name,
                    'type'          => $type,
                    'mana_cost'     => $face->mana_cost ?? $cardData['mana_cost'],
                    'image_uris'    => $face->image_uris ?? $cardData['image_uris'],
                    'oracle_text'   => $face->oracle_text ?? $cardData['oracle_text'],
                ]
            );
        });
    }

    private function getOracleData(): array
    {
        $bulkdataObject = json_decode(file_get_contents(config('services.scryfall.host').config('services.scryfall.bulkdata')));
        $bulkdataTimestamp = Carbon::parse($bulkdataObject->updated_at)->format('YmdHis');

        $oracleFiles = scandir($this->oracleDirectory, SCANDIR_SORT_DESCENDING);
        $lastOracleTimestamp = $oracleFiles[0];
        $lastOracleTimestamp = str_replace('oracle-cards-', '', $lastOracleTimestamp);
        $lastOracleTimestamp = str_replace('.json', '', $lastOracleTimestamp);

        if ($bulkdataTimestamp > $lastOracleTimestamp) {
            $this->info('Downloading new oracle file from external uri.');
            file_put_contents(
                $this->oracleDirectory.'/oracle-cards-'.$bulkdataTimestamp.'.json',
                file_get_contents($bulkdataObject->download_uri)
            );
            $oracleFileTimestamp = $bulkdataTimestamp;
        } else {
            $oracleFileTimestamp = $lastOracleTimestamp;
        }

        return json_decode(file_get_contents($this->oracleDirectory.'/oracle-cards-'.$oracleFileTimestamp.'.json'), true);
    }
}
