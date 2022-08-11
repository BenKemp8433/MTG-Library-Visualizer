<template>
    <v-nav
        @update-grid-size="calculateGridSize($event)"
    />

    <v-library-list
        @fetched-cards="generateImageList($event)"
    />

    <v-library-visuals
        @show="openSidebar($event)"

        :imageList="imageList"
        :gridSize="'grid-cols-' + gridSizeInt"
    />

    <v-sidebar
        @hide="closeSidebar()"

        :cardData="cardData"
        :active="sideBarActive"
    />
</template>

<script>
    export const gameStyles = [
        'commander',
        'legacy',
        'modern',
        'standard',
        'vintage'
    ];
    export const keywordList = {
        'absorb':            'If a source would deal damage to this creature, prevent N of that damage.',
        'adapt':             'If this creature has no +1/+1 counters on it, put N +1/+1 counter(s) on it.',
        'affinity for':      'This spell costs {1} less to cast for each [quality] you control.',
        'afflict':           'Whenever this creature becomes blocked, defending player loses N life.',
        'afterlife':         'When this creature dies, create N 1/1 white and black Spirit creature token(s) with flying.',
        'aftermath':         'Cast this spell only from your graveyard. Then exile it.',
        'amass':             'Put N +1/+1 counters on an Army you control. If you don\'t control one, create a 0/0 black Zombie Army creature token first.',
        'amplify':           'As this creature enters the battlefield, put N +1/+1 counters on it for each [creature type] card you reveal in your hand.',
        'annihilator':       'Whenever this creature attacks, defending player sacrifices N permanents.',
        'ascend':            'If you control ten or more permanents, you get the city’s blessing for the rest of the game.',
        'assemble':          'To assemble a Contraption, put the top card of your Contraption deck face up onto one of your sprockets.',
        'assist':            'Another player can pay up to {X} of this spell\'s cost.',
        'aura swap':         '[Cost]: Exchange this Aura with an Aura card in your hand.',
        'awaken':            'If you cast this card for [cost], also put N +1/+1 counters on target land you control and it becomes a 0/0 Elemental creature with haste. It\'s still a land.',

        'banding':           'Any creatures with banding, and up to one without, can attack in a band. Bands are blocked as a group. If any creatures with banding you control are blocking or being blocked by a creature, you divide that creature\'s combat damage, not its controller, among any of the creatures it\'s being blocked by or is blocking.',
        'battle cry':        'Whenever this creature attacks, each other attacking creature gets +1/+0 until end of turn.',
        'bestow':            'If you cast this card for its bestow cost, it\'s an Aura spell with enchant creature. It becomes a creature again if it\'s not attached to a creature.',
        'blitz':             'If you cast this spell for its blitz cost, it gains haste and "When this creature dies, draw a card". Sacrifice it at the beginning of the next end step.',
        'bloodthirst':       'If an opponent was dealt damage this turn, this permanent enters the battlefield with N +1/+1 counters on it.',
        'boast':             'Activate this ability only if this creature attacked this turn and only once each turn.',
        'bolster':           'Choose a creature you control with the least toughness or tied for least toughness among creatures you control. Put N +1/+1 counters on that creature.',
        'bushido':           'Whenever this creature blocks or becomes blocked, it gets +N/+N until end of turn.',
        'buyback':           'You may pay an additional [cost] as you cast this spell. If the buyback cost was paid, put this spell into its owner’s hand instead of into that player’s graveyard as it resolves.',

        'cascade':           'Return this card from your graveyard to the battlefield. It gains haste. Exile it at the beginning of the next end step or if it would leave the battlefield. Unearth only as a sorcery.',
        'casualty':          'As you cast this spell, you may sacrifice a creature with power N or greater. When you do, copy this spell.',
        'champion a':          'When this enters the battlefield, sacrifice it unless you exile another creature you control. When this leaves the battlefield, that card returns to the battlefield.',
        'changeling':        'This card is every creature type.',
        'cipher':            'Then you may exile this spell card encoded on a creature you control. Whenever that creature deals combat damage to a player, its controller may cast a copy of this card without paying its mana cost.',
        'clash':             'Each clashing player reveals the top card of their library, then puts that card on the top or bottom. A player wins if their card had a higher mana value.',
        'cleave':            'You may cast this spell for its cleave cost. If you do, remove the words in square brackets.',
        'companion':         'If this card is your chosen companion, you may put it into your hand from outside the game for {3} any time you could cast a sorcery.',
        'compleated':        '[Phyrexian hybrid mana] can be paid with M, N, or 2 life. If life was paid, this planeswalker enters with two fewer loyalty counters.',
        'connive':           'Draw N cards, then discard N cards. Put a +1/+1 counter on this creature for each nonland card discarded this way.',
        'convoke':           'Your creatures can help cast this spell. Each creature you tap while casting this spell pays for {1} or one mana of that creature\'s color.',
        'conspire':          'As you cast this spell, you may tap two untapped creatures you control that share a color with it. When you do, copy it and you may choose a new target for the copy.',
        'crew':              'Tap any number of other untapped creatures you control with total power N or greater: This permanent becomes an artifact creature until end of turn.',
        'cumulative upkeep': 'At the beginning of your upkeep, if this permanent is on the battlefield, put an age counter on this permanent. Then you may pay [cost] for each age counter on it. If you don’t, sacrifice it.',
        'cycling':           'Discard this card: Draw a card.',

        'dash':              'You may cast this spell for its dash cost. If you do, it gains haste, and it\'s returned from the battlefield to its owner\'s hand at the beginning of the next end step.',
        'daybound':          'If a player cast no spells during their own turn, it becomes night next turn.',
        'deathtouch':        'Any amount of damage this deals to a creature is enough to destroy it.',
        'decayed':           'This creature can\'t block. When it attacks, sacrifice it at the end of combat.',
        'defender':          'This creature can\'t attack.',
        'delve':             'Each card you exile from your graveyard while casting this spell pays for {1}.',
        'demonstrate':       'When you cast this spell, you may copy it. If you do, choose an opponent to also copy it. Players may choose new targets for their copies.',
        'detain':            'Until your next turn, that permanent can\'t attack or block and its activated abilities can\'t be activated.',
        'dethrone':          'Whenever this creature attacks the player with the most life or tied for most life, put a +1/+1 counter on it.',
        'devoid':            'This card has no color.',
        'devour':            'As this enters the battlefield, you may sacrifice any number of creatures. This creature enters the battlefield with N times that many +1/+1 counters on it.',
        'disturb':           'You may cast this card from your graveyard transformed for its disturb cost.',
        'double agenda':     'Start the game with this conspiracy face down in the command zone and secretly name two different cards. You may turn this conspiracy face up any time and reveal the chosen names.',
        'double strike':     'This creature deals both first-strike and regular combat damage.',
        'dredge':            'If you would draw a card, instead you may mill N cards. If you do, return this card from your graveyard to your hand.',

        'echo':              'At the beginning of your upkeep, if this came under your control since the beginning of your last upkeep, sacrifice it unless you pay its echo cost.',
        'embalm':            '[Cost], Exile this card from your graveyard: Create a token that\'s a copy of it, except it\'s a white Zombie ... with no mana cost. Embalm only as a sorcery.',
        'emerge':            'You may cast this spell by sacrificing a creature and paying the emerge cost reduced by that creature\'s converted mana cost',
        'enchant':           'This card can only be attached to [object]',
        'encore':            '[Cost], Exile this card from your graveyard: For each opponent, create a token copy that attacks that opponent this turn if able. They gain haste. Sacrifice them at the beginning of the next end step. Activate only as a sorcery.',
        'entwine':           'Choose all if you pay the entwine cost.',
        'epic':              'For the rest of the game, you can\'t cast spells. At the beginning of each of your upkeeps, copy this spell except for its epic ability. You may choose new targets for the copy.',
        'equip':             '[Cost]: Attach this permanent to target [object] you control. Activate this ability only any time you could cast a sorcery.',
        'escalate':          'Pay this cost for each mode chosen beyond the first.',
        'escape':            'You may cast this card from your graveyard for its escape cost',
        'eternalize':        '[Cost], Exile this card from your graveyard: Create a token that\'s a copy of it, except it\'s a 4/4 black Zombie ... with no mana cost. Eternalize only as a sorcery.',
        'evoke':             'You may cast this spell for its evoke cost. If you do, it\'s sacrificed when it enters the battlefield.',
        'evolve':            'Whenever a creature enters the battlefield under your control, if that creature has greater power or toughness than this creature, put a +1/+1 counter on this creature.',
        'exalted':           'Whenever a creature you control attacks alone, that creature gets +1/+1 until end of turn.',
        'exert':             'An exerted permanent won\'t untap during your next untap step.',
        'exploit':           'When this creature enters the battlefield, you may sacrifice a creature.',
        'explore':           'Reveal the top card of your library. Put that card into your hand if it\'s a land. Otherwise, put a +1/+1 counter on this creature, then put the card back on top or into your graveyard.',
        'extort':            'Whenever you cast a spell, you may pay {W/B} If you do, each opponent loses 1 life and you gain that much life.',

        'fabricate':         'When this permanent enters the battlefield, you may put N +1/+1 counters on it. If you don\'t, create N 1/1 colorless Servo artifact creature tokens.',
        'fading':            'This creature enters the battlefield with N fade counters on it. At the beginning of your upkeep, remove a fade counter from it. If you can\'t, sacrifice it.',
        'fateseal':          'To fateseal N, look at the top N cards of an opponent\'s library, then put any number of them on the bottom of that player\'s library and the rest on top in any order.',
        'fear':              'This creature can\'t be blocked except by artifact creatures and/or black creatures.',
        'fight':             'Each deals damage equal to its power to the other.',
        'fights':             'Each deals damage equal to its power to the other.',
        'first strike':      'This creature deals combat damage before creatures without first strike.',
        'flanking':          'Whenever a creature without flanking blocks this creature, the blocking creature gets -1/-1 until end of turn.',
        'flash':             'You may cast this spell any time you could cast an instant.',
        'flashback':         'You may cast this card from your graveyard for its flashback cost. Then exile it.',
        'flying':            'This creature can\'t be blocked except by creatures with flying and/or reach.',
        'forecast':          'Activate this ability only during your upkeep and only once each turn.',
        'foretell':          'During your turn, you may pay {2} and exile this card from your hand face down. Cast it on a later turn for its foretell cost.',
        'fortify':           '[Cost]: Attach this Fortification to target land you control. Activate only as a sorcery.',
        'frenzy':            'Whenever this creature attacks and isn\'t blocked, it gets +N/+0 until end of turn.',
        'fuse':              'You may cast one or both halves of this card from your hand.',

        'goad':              'Until your next turn, that creature attacks each combat if able and attacks a player other than you if able.',
        'goaded':            'Until your next turn, that creature attacks each combat if able and attacks a player other than you if able.',
        'graft':             'This creature enters the battlefield with N +1/+1 counters on it. Whenever another creature enters the battlefield, you may move a +1/+1 counter from this creature onto it.',
        'gravestorm':        'When you cast this spell, copy it for each permanent put into a graveyard this turn. You may choose new targets for the copies.',

        'haste':             'This creature can attack and {T} as soon as it comes under your control.',
        'haunt':             'When this creature dies, exile it haunting target creature.',
        'hexproof':          'This permanent can\'t be the target of spells or abilities your opponents control.',
        'hidden agenda':     'Start the game with this conspiracy face down in the command zone and secretly name a card. You may turn this conspiracy face up any time and reveal the chosen name.',
        'hideaway':          'When this permanent enters the battlefield, look at the top N cards of your library, exile one face down, then put the rest on the bottom of your library.',
        'horsemanship':      'This creature can\'t be blocked except by creatures with horsemanship.',

        'improvise':         'Your artifacts can help cast this spell. Each artifact you tap after you\'re done activating mana abilities pays for {1}.',
        'indestructible':    'Damage and effects that say "destroy" don’t destroy this',
        'infect':            'This creature deals damage to creatures in the form of -1/-1 counters and to players in the form of poison counters.',
        'ingest':            'Whenever this creature deals combat damage to a player, that player exiles the top card of their library',
        'intimidate':        'This creature can\'t be blocked except by artifact creatures and/or creatures that share a color with it.',
        'investigate':       'Create a colorless Clue artifact token with "{2}, Sacrifice this artifact: Draw a card."',

        'jump-start':        'You may cast this card from your graveyard by discarding a card in addition to paying its other costs. Then exile this card.',

        'kicker':            'You may pay an additional [cost] as you cast this spell.',

        'landwalk':          'This creature can\'t be blocked as long as defending player controls a [type].',
        'learn':             'You may reveal a Lesson card you own from outside the game and put it into your hand, or discard a card to draw a card.',
        'level up':          '[Cost]: Put a level counter on this. Level up only as a sorcery.',
        'lifelink':          'Damage dealt by this creature also causes you to gain that much life.',
        'living weapon':     'When this Equipment enters the battlefield, create a 0/0 black Phyrexian Germ creature token, then attach this Equipment to it.',

        'madness':           'If you discard this card, discard it into exile. When you do, cast it for its madness cost or put it into your graveyard.',
        'manifest':          'Put it onto the battlefield face down as a 2/2 creature. Turn it face up any time for its mana cost if it\'s a creature card.',
        'megamorph':         'You may cast this card face down as a 2/2 creature for {3}. Turn it face up at any time for its megamorph cost and put a +1/+1 counter on it.',
        'melee':             'Whenever this creature attacks, it gets +1/+1 until end of turn for each opponent you attacked with a creature this combat.',
        'menace':            'This creature can\'t be blocked except by two or more creatures.',
        'mentor':            'Whenever this creature attacks, put a +1/+1 counter on target attacking creature with lesser power.',
        'mill':              'Put the top N cards from the library into your/their graveyard.',
        'mills':              'Put the top N cards from the library into your/their graveyard.',
        'miracle':           'You may cast this card for its Miracle cost when you draw it if it\'s the first card you drew this turn.',
        'modular':           'This enters the battlefield with N +1/+1 counters on it. When it dies, you may put its +1/+1 counters on target artifact creature.',
        'monstrosity':       'If this permanent isn\'t monstrous, put N +1/+1 counters on it and it becomes monstrous.',
        'morph':             'You may cast this card face down as a 2/2 creature for {3}. Turn it face up at any time for its morph cost.',
        'multikicker':       'You may pay an additional [cost] any number of times as you cast this spell.',
        'mutate':            'If you cast this spell for its mutate cost, put it over or under target non-Human creature you own. They mutate into the creature on top plus all abilities from under it.',
        'myriad':            'Whenever this creature attacks, for each opponent other than defending player, you may create a token that\'s a copy of this creature that\'s tapped and attacking that player or a planeswalker they control. If one or more tokens are created this way, exile the tokens at end of combat.',

        'nightbound':        'If a player cast at least two spells during their own turn, it becomes day next turn.',
        'ninjutsu':          'Return an unblocked attacker you control to hand: Put this card onto the battlefield from your hand tapped and attacking.',

        'offering':          'You may cast this card any time you could cast an instant by sacrificing a [Subtype] and paying the difference in mana costs between this and the sacrificed [Subtype]. Mana cost includes color.',
        'outlast':           '[Cost], {T}: Put a +1/+1 counter on this creature. Outlast only as a sorcery.',
        'overload':          'You may cast this spell for its overload cost. If you do, change its text by replacing all instances of \'target\' with \'each\'.',

        'partner':           'You can have two commanders if both have partner.',
        'persist':           'When this creature dies, if it had no -1/-1 counters on it, return it to the battlefield under its owner’s control with a -1/-1 counter on it.',
        'phase out':         'While it’s phased out, it’s treated as though it doesn’t exist. It phases in before its controller untaps during their next untap step.',
        'phases out':        'While it’s phased out, it’s treated as though it doesn’t exist. It phases in before its controller untaps during their next untap step.',
        'phasing':           'This phases in or out before you untap during each of your untap steps. While it\'s phased out, it\'s treated as though it doesn\'t exist.',
        'poisonous':         'Whenever this creature deals combat damage to a player, that player gets N poison counters.',
        'populate':          'Create a token that\'s a copy of a creature token you control.',
        'proliferate':       'Choose any number of permanents and/or players, then give each another counter of each kind already there.',
        'protection':        'This can\'t be blocked, targeted, dealt damage, enchanted, or equipped by anything [quality].',
        'provoke':           'Whenever this creature attacks, you may have target creature defending player controls untap and block it if able.',
        'prowess':           'Whenever you cast a noncreature spell, this creature gets +1/+1 until end of turn.',
        'prowl':             'You may cast this for its prowl cost if you dealt combat damage to a player this turn with a [subtype].',

        'rampage':           'Whenever this creature becomes blocked, it gets +N/+N until end of turn for each creature blocking it beyond the first.',
        'reach':             'This creature can block creatures with flying.',
        'rebound':           'If this spell was cast from your hand, instead of putting it into your graveyard as it resolves, exile it and, at the beginning of your next upkeep, you may cast this card from exile without paying its mana cost.',
        'reconfigure':       '[Cost]: Attach to target creature you control; or unattach from a creature. Reconfigure only as a sorcery. While attached this isn\'t a creature.',
        'recover':           'When a creature is put into your graveyard from the battlefield, you may pay [cost]. If you do, return this card from your graveyard to your hand. Otherwise, exile this card.',
        'regenerate':        'The next time this creature would be destroyed this turn, it isn\'t. Instead tap it, remove all damage from it, and remove it from combat.',
        'reinforce':         '[Cost], Discard this card: Put N +1/+1 counters on target creature.',
        'renown':            'Whenever this creature deals combat damage to a player, if it isn\'t renowned, put N +1/+1 counter on it and it becomes renowned.',
        'replicate':         'When you cast this spell, copy it for each time you paid its replicate cost. You may choose new targets for the copies.',
        'retrace':           'You may cast this card from your graveyard by discarding a land card as an additional cost to cast it.',
        'riot':              'This creature enters the battlefield with your choice of a +1/+1 counter or haste.',
        'ripple':            'When you cast this spell, you may reveal the top N cards of your library. You may cast any revealed cards with the same name as this spell without paying their mana costs. Put the rest on the bottom of your library.',

        'scavenge':          'Exile this card from your graveyard: Put a number of +1/+1 counters equal to this card\'s power on target creature. Scavenge only as a sorcery.',
        'scry':              'Look at the top N cards of your library, then put any number of them on the bottom of your library and the rest on top in any order.',
        'shadow':            'This creature can block or be blocked by only creatures with shadow.',
        'shroud':            'This permanent or player can’t be the target of spells or abilities.',
        'skulk':             'This creature can\'t be blocked by creatures with greater power.',
        'soulbond':          'You may pair this creature with another unpaired creature when either enters the battlefield. They remain paired for as long as you control both of them.',
        'soulshift':         'When this creature dies, you may return target Spirit card with mana value N or less from your graveyard to your hand.',
        'spectacle':         'You may cast this spell for its spectacle cost rather than its mana cost if an opponent lost life this turn.',
        'splice':            'As you cast a(n) [quality] spell, you may reveal this card from your hand and pay its splice cost. If you do, add this card’s effects to that spell.',
        'split second':      'As long as this spell is on the stack, players can\'t cast spells or activate abilities that aren\'t mana abilities.',
        'storm':             'When you cast this spell, copy it for each spell cast before it this turn. You may choose new targets for the copies.',
        'sunburst':          'This enters the battlefield with a [+1/+1 or charge] counter on it for each color of mana spent to cast it.',
        'support':           'Put a +1/+1 counter on each of up to N (other) target creatures.',
        'surge':             'You may cast a spell for its surge cost if you or a teammate have cast another spell in the same turn.',
        'surveil':           'Look at the top N cards of your library, then put any number of them into your graveyard and the rest on top of your library in any order.',
        'suspend':           'Rather than cast this card from your hand, you may pay [cost] and exile it with N time counters on it. At the beginning of your upkeep, remove a time counter. When the last is removed, cast it without paying its mana cost. It has haste.',

        'totem armor':       'If enchanted permanent would be destroyed, instead remove all damage marked on it and destroy this Aura.',
        'training':          'Whenever this creature attacks with another creature with greater power, put a +1/+1 counter on this creature.',
        'trample':           'This creature can deal excess combat damage to a player or planeswalker it\'s attacking.',
        'transfigure':       '[Cost], Sacrifice this permanent: Search your library for a creature card with the same mana value as this permanent, put that card onto the battlefield, then shuffle. Transfigure only as a sorcery.',
        'transmute':         '[Cost], Discard this card: Search your library for a card with the same mana value as this card, reveal it, put it into your hand, then shuffle. Transmute only as a sorcery.',
        'tribute':           'As this creature enters the battlefield, an opponent of your choice may place N +1/+1 counters on it.',

        'undaunted':         'This spell costs {1} less to cast for each opponent.',
        'undying':           'When this creature dies, if it had no +1/+1 counters on it, return it to the battlefield under its owner\'s control with a +1/+1 counter on it.',
        'unearth':           'Return this card from your graveyard to the battlefield. It gains haste. Exile it at the beginning of the next end step or if it would leave the battlefield. Unearth only as a sorcery.',
        'unleash':           'You may have this creature enter the battlefield with a +1/+1 counter on it. It can\'t block as long as it has a +1/+1 counter on it.',

        'vanishing':         'This permanent enters the battlefield with N time counters on it. At the beginning of your upkeep, remove a time counter from it. When the last is removed, sacrifice it.',
        'venture into':      'If you’re in a dungeon, advance to the next room. If you’re not, enter [quality]. Enter the first room or advance to the next room',
        'vigilance':         'Attacking doesn\'t cause this creature to tap.',

        'ward':              'Whenever this creature becomes the target of a spell or ability an opponent controls, counter it unless that player pays [cost].',
        'wither':            'This deals damage to creatures in the form of -1/-1 counters.',
    };
    export const iconList = {
        '{C}': 2,
        '{0}': 2,
        '{1}': 2,
        '{2}': 2,
        '{3}': 2,
        '{4}': 2,
        '{5}': 2,
        '{6}': 2,
        '{7}': 2,
        '{8}': 2,
        '{9}': 2,
        '{10}': 2,
        '{11}': 2,
        '{12}': 2,
        '{13}': 2,
        '{14}': 2,
        '{15}': 2,
        '{16}': 2,
        '{17}': 2,
        '{18}': 2,
        '{19}': 2,
        '{20}': 2,
        '{X}': 2,
        '{Y}': 2,
        '{Z}': 2,

        '{W}': 2,
        '{W/U}': 4,
        '{W/B}': 4,
        '{U}': 2,
        '{U/B}': 4,
        '{U/R}': 4,
        '{B}': 2,
        '{B/R}': 4,
        '{B/G}': 4,
        '{R}': 2,
        '{R/W}': 4,
        '{R/G}': 4,
        '{G}': 2,
        '{G/W}': 4,
        '{G/U}': 4,

        '{W/P}': 2,
        '{W/U/P}': 4,
        '{W/B/P}': 4,
        '{U/P}': 2,
        '{U/B/P}': 4,
        '{U/R/P}': 4,
        '{B/P}': 2,
        '{B/R/P}': 4,
        '{B/G/P}': 4,
        '{R/P}': 2,
        '{R/W/P}': 4,
        '{R/G/P}': 4,
        '{G/P}': 2,
        '{G/W/P}': 4,
        '{G/U/P}': 4,

        '{2/W}': 4,
        '{2/U}': 4,
        '{2/B}': 4,
        '{2/R}': 4,
        '{2/G}': 4,

        '{T}': 2,
        '{Q}': 2,

        '{E}': 0,

        '{S}': 9,
    };

    export default {
        name: "App",

        data() {
            return {
                imageList: Object,
                gridSizeInt: 7,
                cardData: Object,
                sideBarActive: false
            }
        },

        methods: {
            generateImageList(data) {
                this.imageList = data
                this.closeSidebar()
            },

            calculateGridSize(direction) {
                if (direction === 'increase'){
                    if (++this.gridSizeInt > 12) {
                        this.gridSizeInt = 12
                    }
                } else {
                    if (--this.gridSizeInt < 3) {
                        this.gridSizeInt = 3
                    }
                }
            },

            openSidebar(card) {
                this.cardData = card;
                this.sideBarActive = true;
            },

            closeSidebar() {
                this.sideBarActive = false;
            }
        }
    }
</script>
