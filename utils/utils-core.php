<?php

// Function to get page permalink by its template
if(!function_exists('dtlms_get_page_details_by_its_template')) {
	function dtlms_get_page_details_by_its_template( $temlplate ) {

		$page_details = '';

		$pages = get_posts( array(
			'post_type'        => 'page',
			'meta_key'         => '_wp_page_template',
			'meta_value'       => $temlplate,
			'suppress_filters' => 0
		) );

		if ( is_array( $pages ) && count( $pages ) > 0 ) {
			$page         = $pages[0];
			$permalink    = get_permalink( $page->ID );
			$title        = get_the_title( $page->ID );
			$page_details = $title.'|'.$permalink;
		}

		return $page_details;
	}
}

/* ---------------------------------------------------------------------------
 * Site SSL Compatibility
 * --------------------------------------------------------------------------- */
if(!function_exists('dtlms_ssl')) {
	function dtlms_ssl( $echo = false ){
		$ssl = '';
		if( is_ssl() ) $ssl = 's';
		if( $echo ){
			echo ($ssl);
		}
		return $ssl;
	}
}

function dtlms_is_theme_has_codestar() {

	if ( file_exists( get_stylesheet_directory().'/cs-framework/cs-framework.php') ) {
		return true;
	}

	return false;

}

/* ---------------------------------------------------------------------------
 * Fonts
 * --------------------------------------------------------------------------- */
function dtlms_fonts( $type = false )
{
	$fonts = array();

	// system fonts won't be downloaded from Google Fonts
	$fonts['system'] = array(
		'Arial',
		'Georgia',
		'Tahoma',
		'Times',
		'Trebuchet',
		'Verdana',
	);

	$fonts['all'] = array(
		'ABeeZee',
		'Abel',
		'Abril Fatface',
		'Aclonica',
		'Acme',
		'Actor',
		'Adamina',
		'Advent Pro',
		'Aguafina Script',
		'Akronim',
		'Aladin',
		'Aldrich',
		'Alef',
		'Alegreya',
		'Alegreya SC',
		'Alegreya Sans',
		'Alegreya Sans SC',
		'Alex Brush',
		'Alfa Slab One',
		'Alice',
		'Alike',
		'Alike Angular',
		'Allan',
		'Allerta',
		'Allerta Stencil',
		'Allura',
		'Almendra',
		'Almendra Display',
		'Almendra SC',
		'Amarante',
		'Amaranth',
		'Amatic SC',
		'Amethysta',
		'Anaheim',
		'Andada',
		'Andika',
		'Angkor',
		'Annie Use Your Telescope',
		'Anonymous Pro',
		'Antic',
		'Antic Didone',
		'Antic Slab',
		'Anton',
		'Arapey',
		'Arbutus',
		'Arbutus Slab',
		'Architects Daughter',
		'Archivo Black',
		'Archivo Narrow',
		'Arimo',
		'Arizonia',
		'Armata',
		'Artifika',
		'Arvo',
		'Asap',
		'Asset',
		'Astloch',
		'Asul',
		'Atomic Age',
		'Aubrey',
		'Audiowide',
		'Autour One',
		'Average',
		'Average Sans',
		'Averia Gruesa Libre',
		'Averia Libre',
		'Averia Sans Libre',
		'Averia Serif Libre',
		'Bad Script',
		'Balthazar',
		'Bangers',
		'Basic',
		'Battambang',
		'Baumans',
		'Bayon',
		'Belgrano',
		'Belleza',
		'BenchNine',
		'Bentham',
		'Berkshire Swash',
		'Bevan',
		'Bigelow Rules',
		'Bigshot One',
		'Bilbo',
		'Bilbo Swash Caps',
		'Bitter',
		'Black Ops One',
		'Bokor',
		'Bonbon',
		'Boogaloo',
		'Bowlby One',
		'Bowlby One SC',
		'Brawler',
		'Bree Serif',
		'Bubblegum Sans',
		'Bubbler One',
		'Buda',
		'Buenard',
		'Butcherman',
		'Butterfly Kids',
		'Cabin',
		'Cabin Condensed',
		'Cabin Sketch',
		'Caesar Dressing',
		'Cagliostro',
		'Calligraffitti',
		'Cambo',
		'Candal',
		'Cantarell',
		'Cantata One',
		'Cantora One',
		'Capriola',
		'Cardo',
		'Carme',
		'Carrois Gothic',
		'Carrois Gothic SC',
		'Carter One',
		'Caudex',
		'Cedarville Cursive',
		'Ceviche One',
		'Changa One',
		'Chango',
		'Chau Philomene One',
		'Chela One',
		'Chelsea Market',
		'Chenla',
		'Cherry Cream Soda',
		'Cherry Swash',
		'Chewy',
		'Chicle',
		'Chivo',
		'Cinzel',
		'Cinzel Decorative',
		'Clicker Script',
		'Coda',
		'Coda Caption',
		'Codystar',
		'Combo',
		'Comfortaa',
		'Coming Soon',
		'Concert One',
		'Condiment',
		'Content',
		'Contrail One',
		'Convergence',
		'Cookie',
		'Copse',
		'Corben',
		'Courgette',
		'Cousine',
		'Coustard',
		'Covered By Your Grace',
		'Crafty Girls',
		'Creepster',
		'Crete Round',
		'Crimson Text',
		'Croissant One',
		'Crushed',
		'Cuprum',
		'Cutive',
		'Cutive Mono',
		'Damion',
		'Dancing Script',
		'Dangrek',
		'Dawning of a New Day',
		'Days One',
		'Delius',
		'Delius Swash Caps',
		'Delius Unicase',
		'Della Respira',
		'Denk One',
		'Devonshire',
		'Didact Gothic',
		'Diplomata',
		'Diplomata SC',
		'Domine',
		'Donegal One',
		'Doppio One',
		'Dorsa',
		'Dosis',
		'Dr Sugiyama',
		'Droid Sans',
		'Droid Sans Mono',
		'Droid Serif',
		'Duru Sans',
		'Dynalight',
		'EB Garamond',
		'Eagle Lake',
		'Eater',
		'Economica',
		'Ek Mukta',
		'Electrolize',
		'Elsie',
		'Elsie Swash Caps',
		'Emblema One',
		'Emilys Candy',
		'Engagement',
		'Englebert',
		'Enriqueta',
		'Erica One',
		'Esteban',
		'Euphoria Script',
		'Ewert',
		'Exo',
		'Exo 2',
		'Expletus Sans',
		'Fanwood Text',
		'Fascinate',
		'Fascinate Inline',
		'Faster One',
		'Fasthand',
		'Fauna One',
		'Federant',
		'Federo',
		'Felipa',
		'Fenix',
		'Finger Paint',
		'Fira Mono',
		'Fira Sans',
		'Fjalla One',
		'Fjord One',
		'Flamenco',
		'Flavors',
		'Fondamento',
		'Fontdiner Swanky',
		'Forum',
		'Francois One',
		'Freckle Face',
		'Fredericka the Great',
		'Fredoka One',
		'Freehand',
		'Fresca',
		'Frijole',
		'Fruktur',
		'Fugaz One',
		'GFS Didot',
		'GFS Neohellenic',
		'Gabriela',
		'Gafata',
		'Galdeano',
		'Galindo',
		'Gentium Basic',
		'Gentium Book Basic',
		'Geo',
		'Geostar',
		'Geostar Fill',
		'Germania One',
		'Gilda Display',
		'Give You Glory',
		'Glass Antiqua',
		'Glegoo',
		'Gloria Hallelujah',
		'Goblin One',
		'Gochi Hand',
		'Gorditas',
		'Goudy Bookletter 1911',
		'Graduate',
		'Grand Hotel',
		'Gravitas One',
		'Great Vibes',
		'Griffy',
		'Gruppo',
		'Gudea',
		'Habibi',
		'Halant',
		'Hammersmith One',
		'Hanalei',
		'Hanalei Fill',
		'Handlee',
		'Hanuman',
		'Happy Monkey',
		'Headland One',
		'Henny Penny',
		'Herr Von Muellerhoff',
		'Hind',
		'Holtwood One SC',
		'Homemade Apple',
		'Homenaje',
		'IM Fell DW Pica',
		'IM Fell DW Pica SC',
		'IM Fell Double Pica',
		'IM Fell Double Pica SC',
		'IM Fell English',
		'IM Fell English SC',
		'IM Fell French Canon',
		'IM Fell French Canon SC',
		'IM Fell Great Primer',
		'IM Fell Great Primer SC',
		'Iceberg',
		'Iceland',
		'Imprima',
		'Inconsolata',
		'Inder',
		'Indie Flower',
		'Inika',
		'Irish Grover',
		'Istok Web',
		'Italiana',
		'Italianno',
		'Jacques Francois',
		'Jacques Francois Shadow',
		'Jim Nightshade',
		'Jockey One',
		'Jolly Lodger',
		'Josefin Sans',
		'Josefin Slab',
		'Joti One',
		'Judson',
		'Julee',
		'Julius Sans One',
		'Junge',
		'Jura',
		'Just Another Hand',
		'Just Me Again Down Here',
		'Kalam',
		'Kameron',
		'Kantumruy',
		'Karla',
		'Karma',
		'Kaushan Script',
		'Kavoon',
		'Kdam Thmor',
		'Keania One',
		'Kelly Slab',
		'Kenia',
		'Khand',
		'Khmer',
		'Kite One',
		'Knewave',
		'Kotta One',
		'Koulen',
		'Kranky',
		'Kreon',
		'Kristi',
		'Krona One',
		'La Belle Aurore',
		'Laila',
		'Lancelot',
		'Lato',
		'League Script',
		'Leckerli One',
		'Ledger',
		'Lekton',
		'Lemon',
		'Libre Baskerville',
		'Life Savers',
		'Lilita One',
		'Lily Script One',
		'Limelight',
		'Linden Hill',
		'Lobster',
		'Lobster Two',
		'Londrina Outline',
		'Londrina Shadow',
		'Londrina Sketch',
		'Londrina Solid',
		'Lora',
		'Love Ya Like A Sister',
		'Loved by the King',
		'Lovers Quarrel',
		'Luckiest Guy',
		'Lusitana',
		'Lustria',
		'Macondo',
		'Macondo Swash Caps',
		'Magra',
		'Maiden Orange',
		'Mako',
		'Marcellus',
		'Marcellus SC',
		'Marck Script',
		'Margarine',
		'Marko One',
		'Marmelad',
		'Marvel',
		'Mate',
		'Mate SC',
		'Maven Pro',
		'McLaren',
		'Meddon',
		'MedievalSharp',
		'Medula One',
		'Megrim',
		'Meie Script',
		'Merienda',
		'Merienda One',
		'Merriweather',
		'Merriweather Sans',
		'Metal',
		'Metal Mania',
		'Metamorphous',
		'Metrophobic',
		'Michroma',
		'Milonga',
		'Miltonian',
		'Miltonian Tattoo',
		'Miniver',
		'Miss Fajardose',
		'Modern Antiqua',
		'Molengo',
		'Molle',
		'Monda',
		'Monofett',
		'Monoton',
		'Monsieur La Doulaise',
		'Montaga',
		'Montez',
		'Montserrat',
		'Montserrat Alternates',
		'Montserrat Subrayada',
		'Moul',
		'Moulpali',
		'Mountains of Christmas',
		'Mouse Memoirs',
		'Mr Bedfort',
		'Mr Dafoe',
		'Mr De Haviland',
		'Mrs Saint Delafield',
		'Mrs Sheppards',
		'Muli',
		'Mystery Quest',
		'Neucha',
		'Neuton',
		'New Rocker',
		'News Cycle',
		'Niconne',
		'Nixie One',
		'Nobile',
		'Nokora',
		'Norican',
		'Nosifer',
		'Nothing You Could Do',
		'Noticia Text',
		'Noto Sans',
		'Noto Serif',
		'Nova Cut',
		'Nova Flat',
		'Nova Mono',
		'Nova Oval',
		'Nova Round',
		'Nova Script',
		'Nova Slim',
		'Nova Square',
		'Numans',
		'Nunito',
		'Odor Mean Chey',
		'Offside',
		'Old Standard TT',
		'Oldenburg',
		'Oleo Script',
		'Oleo Script Swash Caps',
		'Open Sans',
		'Open Sans Condensed',
		'Oranienbaum',
		'Orbitron',
		'Oregano',
		'Orienta',
		'Original Surfer',
		'Oswald',
		'Over the Rainbow',
		'Overlock',
		'Overlock SC',
		'Ovo',
		'Oxygen',
		'Oxygen Mono',
		'PT Mono',
		'PT Sans',
		'PT Sans Caption',
		'PT Sans Narrow',
		'PT Serif',
		'PT Serif Caption',
		'Pacifico',
		'Paprika',
		'Parisienne',
		'Passero One',
		'Passion One',
		'Pathway Gothic One',
		'Patrick Hand',
		'Patrick Hand SC',
		'Patua One',
		'Paytone One',
		'Peralta',
		'Permanent Marker',
		'Petit Formal Script',
		'Petrona',
		'Philosopher',
		'Piedra',
		'Pinyon Script',
		'Pirata One',
		'Plaster',
		'Play',
		'Playball',
		'Playfair Display',
		'Playfair Display SC',
		'Podkova',
		'Poiret One',
		'Poller One',
		'Poly',
		'Pompiere',
		'Pontano Sans',
		'Port Lligat Sans',
		'Port Lligat Slab',
		'Poppins',
		'Prata',
		'Preahvihear',
		'Press Start 2P',
		'Princess Sofia',
		'Prociono',
		'Prosto One',
		'Puritan',
		'Purple Purse',
		'Quando',
		'Quantico',
		'Quattrocento',
		'Quattrocento Sans',
		'Questrial',
		'Quicksand',
		'Quintessential',
		'Qwigley',
		'Racing Sans One',
		'Radley',
		'Rajdhani',
		'Raleway',
		'Raleway Dots',
		'Rambla',
		'Rammetto One',
		'Ranchers',
		'Rancho',
		'Rationale',
		'Redressed',
		'Reenie Beanie',
		'Revalia',
		'Ribeye',
		'Ribeye Marrow',
		'Righteous',
		'Risque',
		'Roboto',
		'Roboto Condensed',
		'Roboto Slab',
		'Rochester',
		'Rock Salt',
		'Rokkitt',
		'Romanesco',
		'Ropa Sans',
		'Rosario',
		'Rosarivo',
		'Rouge Script',
		'Rozha One',
		'Rubik Mono One',
		'Rubik One',
		'Ruda',
		'Rufina',
		'Ruge Boogie',
		'Ruluko',
		'Rum Raisin',
		'Ruslan Display',
		'Russo One',
		'Ruthie',
		'Rye',
		'Sacramento',
		'Sail',
		'Salsa',
		'Sanchez',
		'Sancreek',
		'Sansita One',
		'Sarina',
		'Sarpanch',
		'Satisfy',
		'Scada',
		'Schoolbell',
		'Seaweed Script',
		'Sevillana',
		'Seymour One',
		'Shadows Into Light',
		'Shadows Into Light Two',
		'Shanti',
		'Share',
		'Share Tech',
		'Share Tech Mono',
		'Shojumaru',
		'Short Stack',
		'Siemreap',
		'Sigmar One',
		'Signika',
		'Signika Negative',
		'Simonetta',
		'Sintony',
		'Sirin Stencil',
		'Six Caps',
		'Skranji',
		'Slabo 13px',
		'Slabo 27px',
		'Slackey',
		'Smokum',
		'Smythe',
		'Sniglet',
		'Snippet',
		'Snowburst One',
		'Sofadi One',
		'Sofia',
		'Sonsie One',
		'Sorts Mill Goudy',
		'Source Code Pro',
		'Source Sans Pro',
		'Source Serif Pro',
		'Special Elite',
		'Spicy Rice',
		'Spinnaker',
		'Spirax',
		'Squada One',
		'Stalemate',
		'Stalinist One',
		'Stardos Stencil',
		'Stint Ultra Condensed',
		'Stint Ultra Expanded',
		'Stoke',
		'Strait',
		'Sue Ellen Francisco',
		'Sunshiney',
		'Supermercado One',
		'Suwannaphum',
		'Swanky and Moo Moo',
		'Syncopate',
		'Tangerine',
		'Taprom',
		'Tauri',
		'Teko',
		'Telex',
		'Tenor Sans',
		'Text Me One',
		'The Girl Next Door',
		'Tienne',
		'Tinos',
		'Titan One',
		'Titillium Web',
		'Trade Winds',
		'Trocchi',
		'Trochut',
		'Trykker',
		'Tulpen One',
		'Ubuntu',
		'Ubuntu Condensed',
		'Ubuntu Mono',
		'Ultra',
		'Uncial Antiqua',
		'Underdog',
		'Unica One',
		'UnifrakturCook',
		'UnifrakturMaguntia',
		'Unkempt',
		'Unlock',
		'Unna',
		'VT323',
		'Vampiro One',
		'Varela',
		'Varela Round',
		'Vast Shadow',
		'Vesper Libre',
		'Vibur',
		'Vidaloka',
		'Viga',
		'Voces',
		'Volkhov',
		'Vollkorn',
		'Voltaire',
		'Waiting for the Sunrise',
		'Wallpoet',
		'Walter Turncoat',
		'Warnes',
		'Wellfleet',
		'Wendy One',
		'Wire One',
		'Yanone Kaffeesatz',
		'Yellowtail',
		'Yeseva One',
		'Yesteryear',
		'Zeyada',
	);

	if( $type ) {
		return $fonts[$type];
	} else {
		return $fonts;
	}

}


if(!function_exists('dtlms_breadcrumb_css')) {
	function dtlms_breadcrumb_css($post_id) {

		$dtlms_breadcrumb = get_post_meta($post_id, 'dtlms-breadcrumb', true);

	    $bg = $co = $repeat = $pos = $attach = $size = $style = '';

	    $bg = (isset($dtlms_breadcrumb['breadcrumb_background']['breadcrumb_image_url']) && $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_image_url'] != '') ? $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_image_url'] : '';
	    $co = (isset($dtlms_breadcrumb['breadcrumb_background']['color']) && $dtlms_breadcrumb['breadcrumb_background']['color'] != '') ? $dtlms_breadcrumb['breadcrumb_background']['color'] : '';

	    if(!empty($bg) || !empty($co)) :
	        $repeat = (isset($dtlms_breadcrumb['breadcrumb_background']['breadcrumb_repeat']) && $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_repeat'] != '') ? $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_repeat'] : 'repeat';
	        $pos    = (isset($dtlms_breadcrumb['breadcrumb_background']['breadcrumb_position']) && $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_position'] != '') ? $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_position'] : 'left top';
	        $attach = (isset($dtlms_breadcrumb['breadcrumb_background']['breadcrumb_attachment']) && $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_attachment'] != '') ? $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_attachment'] : 'scroll';
	        $size   = (isset($dtlms_breadcrumb['breadcrumb_background']['breadcrumb_size']) && $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_size'] != '') ? $dtlms_breadcrumb['breadcrumb_background']['breadcrumb_size'] : 'auto';
	    else:
	        $bgoptions = cs_get_option( 'breadcrumb_background' );
	        $bg         = !empty( $bgoptions['image'] ) ? $bgoptions['image'] : '';
	        $attach     = !empty( $bgoptions['attachment'] ) ? $bgoptions['attachment'] :'scroll';
	        $pos        = !empty( $bgoptions['position'] ) ? $bgoptions['position'] :'left top';
	        $size       = !empty( $bgoptions['size'] ) ? $bgoptions['size'] :'auto';
	        $repeat     = !empty( $bgoptions['repeat'] ) ? $bgoptions['repeat'] :'repeat';
	        $co         = !empty( $bgoptions['color'] ) ? $bgoptions['color'] : '';
	    endif;

		$style = !empty($bg) ? "background-image: url($bg); " : "";
		$style .= !empty($pos) ? "background-position: $pos; " : "";
		$style .= !empty($size) ? "background-size: $size; " : "";
		$style .= !empty($repeat) ? "background-repeat: $repeat; " : "";
		$style .= !empty($attach) ? "background-attachment: $attach; " : "";
	    $style .= !empty($co) ? "background-color:$co;" : "";

	    return $style;
	}
}