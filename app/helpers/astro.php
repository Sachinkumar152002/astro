<?php

function zodiacSigns(){
    $signs = [
        [
            "name"=>"Aries",
            "image_id"=>"1",
            "sd"=> 21,
            "sm"=> 3,
            "ed"=> 19,
            "em"=> 4,
        ],
        [
            "name"=>"Taurus",
            "image_id"=>"2",
            "sd"=> 20,
            "sm"=> 4,
            "ed"=> 20,
            "em"=> 5,
        ],
        [
            "name"=>"Gemini",
            "image_id"=>"3",
            "sd"=> 21,
            "sm"=> 5,
            "ed"=> 20,
            "em"=> 6,
        ],
        [
            "name"=>"Cancer",
            "image_id"=>"4",
            "sd"=> 21,
            "sm"=> 6,
            "ed"=> 22,
            "em"=> 7,
        ],
        [
            "name"=>"Leo",
            "image_id"=>"5",
            "sd"=> 23,
            "sm"=> 7,
            "ed"=> 22,
            "em"=> 8,
        ],
        [
            "name"=>"Virgo",
            "image_id"=>"6",
            "sd"=> 23,
            "sm"=> 8,
            "ed"=> 22,
            "em"=> 9,
        ],
        [
            "name"=>"Libra",
            "image_id"=>"7",
            "sd"=> 23,
            "sm"=> 9,
            "ed"=> 22,
            "em"=> 10,
        ],
        [
            "name"=>"Scorpio",
            "image_id"=>"8",
            "sd"=> 23,
            "sm"=> 10,
            "ed"=> 219,
            "em"=> 11,
        ],
        [
            "name"=>"Sagittarius",
            "image_id"=>"9",
            "sd"=> 22,
            "sm"=> 11,
            "ed"=> 21,
            "em"=> 12,
        ],
        [
            "name"=>"Capricorn",
            "image_id"=>"10",
            "sd"=> 22,
            "sm"=> 12,
            "ed"=> 19,
            "em"=> 1,
        ],
        [
            "name"=>"Aquarius",
            "image_id"=>"11",
            "sd"=> 20,
            "sm"=> 1,
            "ed"=> 18,
            "em"=> 2,
        ],
        [
            "name"=>"Pisces",
            "image_id"=>"12",
            "sd"=> 19,
            "sm"=> 2,
            "ed"=> 20,
            "em"=> 3,
        ],
    ];
    return $signs;
}

function astroMonths()
{
    $months = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
    return $months;
}

function monthDays() {
    $days = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
      ];
    return $days;
}

function weekDays($n = null){
    $days = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        0 => 'Sunday',
    ];
    return  is_null($n)  ? $days : $days[$n];
}

function planets($n = null){
    $planets = [
        'Surya',
        'Moon',
        'Mercury',
        'Venus',
        'Mars',
        'Jupiter',
        'Saturn',
        'Rahu',
        'Ketu',
    ];

    return  is_null($n)  ? $planets : $planets[$n];
}

function zodiacSignsMorePagesTypes() {
    $types = [
        'Personality',
        'Lover',
        'Professional',
        'Teen',
        'Man',
        'Woman',
        'Traits',
        'Season',
        'Moon'
    ];
    return $types;
}

function planetsPagesAr(){
    $planets = [
        ['name'=>'Planets', 'is_transit'=>0, 'is_retrograde'=>0, 'is_grahan'=>0, 'grahan_name'=>'', 'type'=>'page'],
        ['name'=>'Transit', 'is_transit'=>0, 'is_retrograde'=>0, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'sub-page'],
        ['name'=>'Retrograde', 'is_transit'=>0, 'is_retrograde'=>0, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'sub-page'],
        ['name'=>'Sun', 'is_transit'=>1, 'is_retrograde'=>0, 'is_grahan'=>1, 'grahan_name'=>'Surya grahan',  'type'=>'planet'],
        ['name'=>'Moon', 'is_transit'=>1, 'is_retrograde'=>0, 'is_grahan'=>1, 'grahan_name'=>'Chandra Grahan',  'type'=>'planet'],
        ['name'=>'Mercury', 'is_transit'=>1, 'is_retrograde'=>0, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'planet'],
        ['name'=>'Venus', 'is_transit'=>1, 'is_retrograde'=>1, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'planet'],
        ['name'=>'Mars', 'is_transit'=>1, 'is_retrograde'=>1, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'planet'],
        ['name'=>'Jupiter', 'is_transit'=>1, 'is_retrograde'=>1, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'planet'],
        ['name'=>'Saturn', 'is_transit'=>1, 'is_retrograde'=>1, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'planet'],
        ['name'=>'Rahu', 'is_transit'=>1, 'is_retrograde'=>1, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'planet'],
        ['name'=>'Ketu', 'is_transit'=>0, 'is_retrograde'=>1, 'is_grahan'=>0, 'grahan_name'=>'',  'type'=>'planet'],
    ];
    return $planets;
}

function palmReadingAr(){
    $palms = [
        ['name'=>'Palm Reading','type'=>'page', 'slug'=>'palmreading'],
        ['name'=>'Types of Hands','type'=>'sub-page', 'slug'=>'hands'],
        ['name'=>'Types of Fingers','type'=>'sub-page', 'slug'=>'finger'],
        ['name'=>'Types of Nails','type'=>'sub-page', 'slug'=>'nails'],
        ['name'=>'Bracelet Line','type'=>'sub-page', 'slug'=>'bracelet-line'],
        ['name'=>'Children Line','type'=>'sub-page', 'slug'=>'children-line'],
        ['name'=>'Fate Line','type'=>'sub-page', 'slug'=>'fate-line'],
        ['name'=>'Head line','type'=>'sub-page', 'slug'=>'head-line'],
        ['name'=>'Heart Line','type'=>'sub-page', 'slug'=>'heart-line'],
        ['name'=>'Life Line','type'=>'sub-page', 'slug'=>'life-line'],
        ['name'=>'Marriage Line','type'=>'sub-page', 'slug'=>'marriage-line'],
        ['name'=>'Simian Line','type'=>'sub-page', 'slug'=>'simian-line'],
        ['name'=>'Sun Line','type'=>'sub-page', 'slug'=>'sun-line'],
        ['name'=>'Travel line','type'=>'sub-page', 'slug'=>'travel-line'],
    ];
    return $palms;
}

function remediesAr(){
    $remedies = [
        ['name'=>'Remedies', 'slug'=>'remedies', 'type'=>'page'],
        ['name'=>'Planetary', 'slug'=>'planetary', 'type'=>'sub-page'],
        ['name'=>'Sun', 'slug'=>'sun', 'type'=>'planetary'],
        ['name'=>'Moon', 'slug'=>'moon', 'type'=>'planetary'],
        ['name'=>'Mercury', 'slug'=>'mercury', 'type'=>'planetary'],
        ['name'=>'Jupiter', 'slug'=>'jupiter', 'type'=>'planetary'],
        ['name'=>'Venus', 'slug'=>'venus', 'type'=>'planetary'],
        ['name'=>'Saturn', 'slug'=>'saturn', 'type'=>'planetary'],
        ['name'=>'Rahu', 'slug'=>'rahu', 'type'=>'planetary'],
        ['name'=>'Ketu', 'slug'=>'ketu', 'type'=>'planetary'],
        ['name'=>'Mars', 'slug'=>'mars', 'type'=>'planetary'],
        ['name'=>'Gemstones', 'slug'=>'gems', 'type'=>'sub-page'],
        ['name'=>'Blue Sapphire', 'slug'=>'sapphire', 'type'=>'gems'],
        ['name'=>'Blue Topaz', 'slug'=>'topaz', 'type'=>'gems'],
        ['name'=>'Diamond', 'slug'=>'diamond', 'type'=>'gems'],
        ['name'=>'Emerald', 'slug'=>'emerald', 'type'=>'gems'],
        ['name'=>'Moonstone', 'slug'=>'moonstone', 'type'=>'gems'],
        ['name'=>'Onyx', 'slug'=>'onyx', 'type'=>'gems'],
        ['name'=>'Opal', 'slug'=>'opal', 'type'=>'gems'],
        ['name'=>'Pearl', 'slug'=>'pearl', 'type'=>'gems'],
        ['name'=>'Ruby', 'slug'=>'ruby', 'type'=>'gems'],
        ['name'=>'Sunstone', 'slug'=>'sunstone', 'type'=>'gems'],
        ['name'=>'Turquoise', 'slug'=>'turquoise', 'type'=>'gems'],
        ['name'=>'Rudraksha', 'slug'=>'rudraksha', 'type'=>'sub-page'],
        ['name'=>'1 Mukhi Rudraksha', 'slug'=>'1-mukhi', 'type'=>'rudraksha'],
        ['name'=>'2 Mukhi Rudraksha', 'slug'=>'2-mukhi', 'type'=>'rudraksha'],
        ['name'=>'3 Mukhi Rudraksha', 'slug'=>'3-mukhi', 'type'=>'rudraksha'],
        ['name'=>'4 Mukhi Rudraksha', 'slug'=>'4-mukhi', 'type'=>'rudraksha'],
        ['name'=>'5 Mukhi Rudraksha', 'slug'=>'5-mukhi', 'type'=>'rudraksha'],
        ['name'=>'6 Mukhi Rudraksha', 'slug'=>'6-mukhi', 'type'=>'rudraksha'],
        ['name'=>'7 Mukhi Rudraksha', 'slug'=>'7-mukhi', 'type'=>'rudraksha'],
        ['name'=>'8 Mukhi Rudraksha', 'slug'=>'8-mukhi', 'type'=>'rudraksha'],
        ['name'=>'9 Mukhi Rudraksha', 'slug'=>'9-mukhi', 'type'=>'rudraksha'],
        ['name'=>'10 Mukhi Rudraksha', 'slug'=>'10-mukhi', 'type'=>'rudraksha'],
        ['name'=>'11 Mukhi Rudraksha', 'slug'=>'11-mukhi', 'type'=>'rudraksha'],
        ['name'=>'12 Mukhi Rudraksha', 'slug'=>'12-mukhi', 'type'=>'rudraksha'],
        ['name'=>'13 Mukhi Rudraksha', 'slug'=>'13-mukhi', 'type'=>'rudraksha'],
        ['name'=>'14 Mukhi Rudraksha', 'slug'=>'14-mukhi', 'type'=>'rudraksha'],
        ['name'=>'15 Mukhi Rudraksha', 'slug'=>'15-mukhi', 'type'=>'rudraksha'],
        ['name'=>'16 Mukhi Rudraksha', 'slug'=>'16-mukhi', 'type'=>'rudraksha'],
        ['name'=>'17 Mukhi Rudraksha', 'slug'=>'17-mukhi', 'type'=>'rudraksha'],
        ['name'=>'18 Mukhi Rudraksha', 'slug'=>'18-mukhi', 'type'=>'rudraksha'],
        ['name'=>'19 Mukhi Rudraksha', 'slug'=>'19-mukhi', 'type'=>'rudraksha'],
        ['name'=>'20 Mukhi Rudraksha', 'slug'=>'20-mukhi', 'type'=>'rudraksha'],
        ['name'=>'21 Mukhi Rudraksha', 'slug'=>'21-mukhi', 'type'=>'rudraksha'],
        ['name'=>'Yantras', 'slug'=>'yantras', 'type'=>'sub-page'],
        ['name'=>'Mahalaxmi Yantra', 'slug'=>'mahalaxmi', 'type'=>'yantras'],
        ['name'=>'Sri Yantra', 'slug'=>'sri', 'type'=>'yantras'],
        ['name'=>'Mahamritunjay Yantra', 'slug'=>'mahamritunjay', 'type'=>'yantras'],
        ['name'=>'Kuber Yantra', 'slug'=>'kuber', 'type'=>'yantras'],
        ['name'=>'Bagla Mukhi Yantra', 'slug'=>'bagla-mukhi', 'type'=>'yantras'],
        ['name'=>'Saraswati Yantra', 'slug'=>'saraswati', 'type'=>'yantras'],
        ['name'=>'Nav Grah Yantra', 'slug'=>'nav-grah', 'type'=>'yantras'],
        ['name'=>'Vastu Dosh Yantra', 'slug'=>'vastu-dosh', 'type'=>'yantras'],
        ['name'=>'Sri Durga Yantra', 'slug'=>'durga', 'type'=>'yantras'],
        ['name'=>'Chandra Yantra', 'slug'=>'chandra', 'type'=>'yantras'],
        ['name'=>'Ganpati Yantra', 'slug'=>'ganpati', 'type'=>'yantras'],
        ['name'=>'Mangal Yantra', 'slug'=>'mangal', 'type'=>'yantras'],
        ['name'=>'Rahu Yantra', 'slug'=>'rahu', 'type'=>'yantras'],
        ['name'=>'Shani Yantra', 'slug'=>'shani', 'type'=>'yantras'],
        ['name'=>'Gayatri Yantra', 'slug'=>'gayatri', 'type'=>'yantras'],
        ['name'=>'Hanuman Yantra', 'slug'=>'hanuman', 'type'=>'yantras'],
        ['name'=>'Mahakali Yantra', 'slug'=>'mahakali', 'type'=>'yantras'],
        ['name'=>'Shukra Yantra', 'slug'=>'shukra', 'type'=>'yantras'],
        ['name'=>'Surya Yantra', 'slug'=>'surya', 'type'=>'yantras'],
        ['name'=>'Vyapar vriddhi', 'slug'=>'vyapar-vriddhi', 'type'=>'yantras'],
        ['name'=>'Budh', 'slug'=>'budh', 'type'=>'yantras'],
        ['name'=>'Guru', 'slug'=>'guru', 'type'=>'yantras'],
    ];
    return $remedies;
}

function vastuAr(){
    $vastu = [
        ['name'=>'Vastu Shastra', 'slug'=>'vastu', 'type'=>'page'],
    ];
    return $vastu;
}
// function vastuAr(){
//     $vastu = [
//         ['name'=>'Vastu Shastra', 'slug'=>'vastu', 'type'=>'page'],
//         ['name'=>'Vidya', 'slug'=>'home', 'type'=>'sub-page'],
//         ['name'=>'New House', 'slug'=>'house', 'type'=>'sub-page'],
//         ['name'=>'Office', 'slug'=>'office', 'type'=>'sub-page'],
//         ['name'=>'Factory', 'slug'=>'factory', 'type'=>'sub-page'],
//         ['name'=>'Bathroom', 'slug'=>'bathroom', 'type'=>'sub-page'],
//         ['name'=>'Kitchen', 'slug'=>'kitchen', 'type'=>'sub-page'],
//         ['name'=>'Study Room', 'slug'=>'study-room', 'type'=>'sub-page'],
//         ['name'=>'Pooja room', 'slug'=>'pooja-room', 'type'=>'sub-page'],
//         ['name'=>'Shop', 'slug'=>'shop', 'type'=>'sub-page'],
//         ['name'=>'Dining Room', 'slug'=>'dining-room', 'type'=>'sub-page'],
//         ['name'=>'Guest Room', 'slug'=>'guest-room', 'type'=>'sub-page'],
//         ['name'=>'Children Room', 'slug'=>'children-room', 'type'=>'sub-page'],
//         ['name'=>'Career', 'slug'=>'career', 'type'=>'sub-page'],
//         ['name'=>'Marriage Life', 'slug'=>'married-life', 'type'=>'sub-page'],
//         ['name'=>'Health', 'slug'=>'health', 'type'=>'sub-page'],
//         ['name'=>'Colors for Home', 'slug'=>'colors-for-home', 'type'=>'sub-page'],
//         ['name'=>'Positive Energy', 'slug'=>'positive-energy', 'type'=>'sub-page'],
//         ['name'=>'Business', 'slug'=>'business', 'type'=>'sub-page'],
//         ['name'=>'Plants', 'slug'=>'plants-trees', 'type'=>'sub-page'],
//         ['name'=>'Happiness', 'slug'=>'happiness', 'type'=>'sub-page'],
//         ['name'=>'Peace', 'slug'=>'peace', 'type'=>'sub-page'],
//         ['name'=>'Wealth', 'slug'=>'wealth', 'type'=>'sub-page'],
//     ];
//     return $vastu;
// }
function cms2Arr(){
    $vastu = [
        ['image_id'=>0, 'slug'=>'nakshtra-gold-software', 'page'=>'software', 'description'=>'Description', 'title'=>'NG Software'],
        ['image_id'=>0, 'slug'=>'astrology', 'page'=>'astrology', 'description'=>'Description', 'title'=>'Astrology'],
    ];
    return $vastu;
}

function weddingAr(){
    $wedding = [
        ['name'=>'Indian Wedding: Ceremonies and Types. Know It All!', 'slug'=>'wedding', 'type'=>'page', 'sub_type'=>''],
        ['name'=>'Kashmiri', 'slug'=>'kashmiri', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Rajput', 'slug'=>'rajput', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Kumaoni', 'slug'=>'kumaoni', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Odiya', 'slug'=>'odiya', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Kannada', 'slug'=>'kannada', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Punjabi', 'slug'=>'punjabi', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Assamese', 'slug'=>'assamese', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Kerala', 'slug'=>'kerala', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Sikh', 'slug'=>'sikh', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Maharashtrian', 'slug'=>'maharashtrian', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Tamil', 'slug'=>'tamil', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Telugu', 'slug'=>'telugu', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Marwari', 'slug'=>'marwari', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Jain', 'slug'=>'jain', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Bihari', 'slug'=>'bihari', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Konkani', 'slug'=>'konkani', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Gujarati', 'slug'=>'gujarati', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Sindhi', 'slug'=>'sindhi', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Bengali', 'slug'=>'bengali', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Himachali', 'slug'=>'himachali', 'type'=>'sub-page', 'sub_type'=>'tradition'],
        ['name'=>'Groom', 'slug'=>'groom', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Bride', 'slug'=>'bride', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Wedding Ceremony', 'slug'=>'wedding-ceremony', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Engagement Ceremony', 'slug'=>'engagement', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Haldi Ceremony', 'slug'=>'haldi', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Sangeet Ceremony', 'slug'=>'sangeet', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Jai Mala', 'slug'=>'jai-mala', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Mandap', 'slug'=>'mandap', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Saptapadi Ceremony', 'slug'=>'saptapadi', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Kanyadaan Ceremony', 'slug'=>'kanyadaan', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Vidaai Ceremony', 'slug'=>'vidaai', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Griha Pravesh Ceremony', 'slug'=>'griha-pravesh', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Wedding Reception', 'slug'=>'reception', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Mooh Dikhai Ceremony', 'slug'=>'mooh-dikhai', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Ganesha Puja', 'slug'=>'ganesha-puja', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Suhaag Raat', 'slug'=>'suhaag-raat', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Arranged Marriage', 'slug'=>'arranged-marriage', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Love Marriage', 'slug'=>'love-marriage', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Civil marriage', 'slug'=>'civil-marriage', 'type'=>'sub-page', 'sub_type'=>'rituals'],
        ['name'=>'Wedding Traditions in India', 'slug'=>'traditions', 'type'=>'sub-page', 'sub_type'=>'rituals'],
    ];
    return $wedding;
}

function pujaAr(){
    $puja = [
        ['name'=>'Pooja Vidhi & Its Significance', 'slug'=>'pooja-vidhi', 'type'=>'page'],
        ['name'=>'Bhoomi Poojan', 'slug'=>'bhoomi-poojan', 'type'=>'sub-page'],
        ['name'=>'Graha Pravesh', 'slug'=>'graha-pravesh', 'type'=>'sub-page'],
        ['name'=>'Laghu Rudra Pooja', 'slug'=>'laghu-rudra-pooja', 'type'=>'sub-page'],
        ['name'=>'Lakshmi Poojan', 'slug'=>'lakshmi-poojan', 'type'=>'sub-page'],
        ['name'=>'Naamkaran', 'slug'=>'naamkaran', 'type'=>'sub-page'],
        ['name'=>'Navgraha Pooja', 'slug'=>'navgraha-pooja', 'type'=>'sub-page'],
        ['name'=>'Shiv Puran', 'slug'=>'shiv-puran', 'type'=>'sub-page'],
        ['name'=>'Sahasra Chandi Yagna', 'slug'=>'sahasra-chandi-yagna', 'type'=>'sub-page'],
        ['name'=>'Shri Satya Narayan Pooja', 'slug'=>'satya-narayan-pooja', 'type'=>'sub-page'],
        ['name'=>'Shat Chandi Yagna', 'slug'=>'shat-chandi-yagna', 'type'=>'sub-page'],
        ['name'=>'Vaastu Shanti', 'slug'=>'vaastu-shanti', 'type'=>'sub-page'],
    ];
    return $puja;
}

function mantrasAr(){
    $mantras = [
        ['name'=>'Mantras & Chants', 'slug'=>'mantra', 'type'=>'page'],
        ['name'=>'Kamala Mata Mantra', 'slug'=>'kamlamata-mantra', 'type'=>'sub-page'],
        ['name'=>'Kaali Mantra', 'slug'=>'kaali-mantra', 'type'=>'sub-page'],
        ['name'=>'Kuber Mantra', 'slug'=>'kuber-mantra', 'type'=>'sub-page'],
        ['name'=>'Shri Ganesh Mantra', 'slug'=>'ganesh-mantra', 'type'=>'sub-page'],
        ['name'=>'Gayatri Mantra', 'slug'=>'gayatri-mantra', 'type'=>'sub-page'],
        ['name'=>'Diwali Pooja Mantra', 'slug'=>'diwali-puja--mantra', 'type'=>'sub-page'],
        ['name'=>'Lakshmi Mantra', 'slug'=>'laxmi-mantra', 'type'=>'sub-page'],
        ['name'=>'Shanti Paath Mantra', 'slug'=>'shanti-mantra', 'type'=>'sub-page'],
        ['name'=>'Durga Mantra', 'slug'=>'durga-mantra', 'type'=>'sub-page'],
        ['name'=>'Saraswati Mantra', 'slug'=>'saraswati-mantra', 'type'=>'sub-page'],
        ['name'=>'Shani Mantra', 'slug'=>'shani-mantra', 'type'=>'sub-page'],
        ['name'=>'Hanuman Mantra', 'slug'=>'hanuman-mantra', 'type'=>'sub-page'],
        ['name'=>'Surya Mantra', 'slug'=>'surya-mantra', 'type'=>'sub-page'],
        ['name'=>'Krishna Mantra', 'slug'=>'krishna-mantra', 'type'=>'sub-page'],
        ['name'=>'Mahamrityunjaya Mantra', 'slug'=>'mahamrityunjaya-mantra', 'type'=>'sub-page'],
        ['name'=>'Om Mantra', 'slug'=>'om-mantra', 'type'=>'sub-page'],
        ['name'=>'Lakshmi-Ganesh Mantra', 'slug'=>'laxmi-ganesh-mantra', 'type'=>'sub-page'],
        ['name'=>'Shiva Mantra', 'slug'=>'shiv-mantra', 'type'=>'sub-page'],

    ];
    return $mantras;
}
function chakraAr(){
    $chakra = [
        ['name'=>'Chakras', 'slug'=>'chakra', 'type'=>'page'],
        ['name'=>'Root Chakra', 'slug'=>'root', 'type'=>'sub-page'],
        ['name'=>'Sacral Chakra', 'slug'=>'sacral', 'type'=>'sub-page'],
        ['name'=>'Solar Plexus', 'slug'=>'solar', 'type'=>'sub-page'],
        ['name'=>'Heart Chakra', 'slug'=>'heart', 'type'=>'sub-page'],
        ['name'=>'Throat Chakra', 'slug'=>'throat', 'type'=>'sub-page'],
        ['name'=>'Third-Eye Chakra', 'slug'=>'eye', 'type'=>'sub-page'],
        ['name'=>'Crown Chakra', 'slug'=>'crown', 'type'=>'sub-page'],
    ];
    return $chakra;
}


function colorAr(){
    $color = [
        ['name'=>'Color Therapy', 'slug'=>'color-therapy', 'type'=>'page'],
        ['name'=>'The Color Black', 'slug'=>'Black','type'=>'sub-page'],
        ['name'=>'The Color Blue', 'slug'=>'Blue','type'=>'sub-page'],
        ['name'=>'The Color Brown', 'slug'=>'Brown','type'=>'sub-page'],
        ['name'=>'The Color Gold', 'slug'=>'Gold','type'=>'sub-page'],
        ['name'=>'The Color Gray', 'slug'=>'Gray','type'=>'sub-page'],
        ['name'=>'The Color Green', 'slug'=>'Green','type'=>'sub-page'],
        ['name'=>'The Color Indigo', 'slug'=>'Indigo','type'=>'sub-page'],
        ['name'=>'The Color Magenta', 'slug'=>'Magenta','type'=>'sub-page'],
        ['name'=>'The Color Orange', 'slug'=>'Orange','type'=>'sub-page'],
        ['name'=>'The Color Pink', 'slug'=>'Pink','type'=>'sub-page'],
        ['name'=>'The Color Purple', 'slug'=>'Purple','type'=>'sub-page'],
        ['name'=>'The Color Red', 'slug'=>'Red','type'=>'sub-page'],
        ['name'=>'The Color Silver', 'slug'=>'Silver','type'=>'sub-page'],
        ['name'=>'The Color Turquoise', 'slug'=>'Turquoise','type'=>'sub-page'],
        ['name'=>'The Color White', 'slug'=>'White','type'=>'sub-page'],
        ['name'=>'The Color Yellow', 'slug'=>'Yellow','type'=>'sub-page'],
    ];
    return $color;
}

function meditationAr(){
    $meditation = [
        ['name'=>'Meditation', 'slug'=>'meditation', 'type'=>'page'],
        ['name'=>'Vipassana Meditation', 'slug'=>'vipassana', 'type'=>'sub-page'],
        ['name'=>'Morning Meditation', 'slug'=>'morning', 'type'=>'sub-page'],
        ['name'=>'Buddhist Meditation', 'slug'=>'buddhist', 'type'=>'sub-page'],
        ['name'=>'Chakra Meditation', 'slug'=>'chakra', 'type'=>'sub-page'],
        ['name'=>'Yoga Meditation', 'slug'=>'yoga', 'type'=>'sub-page'],
        ['name'=>'Mantra Meditation', 'slug'=>'mantra', 'type'=>'sub-page'],
        ['name'=>'Metta Meditation', 'slug'=>'metta', 'type'=>'sub-page'],
        ['name'=>'Kundalini Meditation', 'slug'=>'kundalini', 'type'=>'sub-page'],
        ['name'=>'Vedic Meditation', 'slug'=>'vedic', 'type'=>'sub-page'],
        ['name'=>'Yoga Nidra', 'slug'=>'yoga-nidra', 'type'=>'sub-page'],
        ['name'=>'Transcendental Meditation', 'slug'=>'transcendental', 'type'=>'sub-page'],
        ['name'=>'Kriya Yoga', 'slug'=>'kriya-yoga', 'type'=>'sub-page'],
        ['name'=>'Shambhavi Mahamudra', 'slug'=>'shambhavi-mahamudra', 'type'=>'sub-page'],
        ['name'=>'Meditation for Beginners' , 'slug'=>'beginners', 'type'=>'sub-page'],
        ['name'=>'Meditation for Healing' , 'slug'=>'healing', 'type'=>'sub-page'],
        ['name'=>'Meditation for Stress' , 'slug'=>'stress', 'type'=>'sub-page'],
        ['name'=>'Meditation for Sleep' , 'slug'=>'sleep', 'type'=>'sub-page'],
        ['name'=>'Meditation for mindfulness' , 'slug'=>'mindfulness', 'type'=>'sub-page'],
        ['name'=>'Meditation for Relaxation' , 'slug'=>'relaxation', 'type'=>'sub-page'],
        ['name'=>'Meditation for Calm' , 'slug'=>'calm', 'type'=>'sub-page'],
        ['name'=>'Walking Meditation', 'slug'=>'walking', 'type'=>'sub-page'],
        ['name'=>'Meditation for Weight Loss' , 'slug'=>'weight-loss', 'type'=>'sub-page'],
        ['name'=>'Meditation for Kids'  , 'slug'=>'kids', 'type'=>'sub-page'],
    ];
    return $meditation;
}

function yogaAr(){
    $yoga = [
        ['name'=>'Yoga', 'slug'=>'yoga', 'type'=>'page'],
        ['name'=>'Pranayam','slug'=>'pranayam', 'type'=>'sub-page'],
        ['name'=>'Surya Namaskar','slug'=>'surya-namaskar', 'type'=>'sub-page'],
        ['name'=>'Hatha Yoga','slug'=>'hatha-yoga', 'type'=>'sub-page'],
        ['name'=>'Ashtanga yoga','slug'=>'ashtanga-yoga', 'type'=>'sub-page'],
        ['name'=>'Hot yoga','slug'=>'hot-yoga', 'type'=>'sub-page'],
        ['name'=>'Kapalbhati','slug'=>'kapalbhati', 'type'=>'sub-page'],
        ['name'=>'Bikram yoga','slug'=>'bikram-yoga', 'type'=>'sub-page'],
        ['name'=>'Kriya yoga','slug'=>'kriya-yoga', 'type'=>'sub-page'],
        ['name'=>'Kundalini','slug'=>'kundalini', 'type'=>'sub-page'],
        ['name'=>'Yoga nidra','slug'=>'yoga-nidra', 'type'=>'sub-page'],
        ['name'=>'Anulom vilom','slug'=>'anulom-vilom', 'type'=>'sub-page'],
        ['name'=>'Nadi shodhana','slug'=>'nadi-shodhana', 'type'=>'sub-page'],
        ['name'=>'Bhastrika','slug'=>'bhastrika', 'type'=>'sub-page'],
        ['name'=>'Yin yoga','slug'=>'yin-yoga', 'type'=>'sub-page'],
        ['name'=>'Yoga for kids','slug'=>'yoga-for-kids', 'type'=>'sub-page'],
        ['name'=>'Yoga for women','slug'=>'yoga-for-women', 'type'=>'sub-page'],
        ['name'=>'Yoga for men','slug'=>'yoga-for-men', 'type'=>'sub-page'],
        ['name'=>'Yoga for pregnancy','slug'=>'yoga-for-pregnancy', 'type'=>'sub-page'],
        ['name'=>'Yoga day','slug'=>'yoga-day', 'type'=>'sub-page'],
    ];
    return $yoga;
}

function festivalAr(){
    $festival = [
        ['name'=>'Festivals', 'slug'=>'festival', 'type'=>'page'],
        ['name'=>'Dhanteras', 'slug'=>'dhanteras', 'type'=>'sub-page'],
        ['name'=>'Diwali', 'slug'=>'diwali', 'type'=>'sub-page'],
        ['name'=>'Govardhan Pooja', 'slug'=>'govardhan-puja', 'type'=>'sub-page'],
        ['name'=>'Bhai Dooj', 'slug'=>'bhai-dooj', 'type'=>'sub-page'],
        ['name'=>'Tulsi Vivah', 'slug'=>'tulsi-vivah', 'type'=>'sub-page'],
        ['name'=>'Guru Nanak Jayanti', 'slug'=>'guru-nanak-jayanti', 'type'=>'sub-page'],
        ['name'=>'Christmas', 'slug'=>'christmas', 'type'=>'sub-page'],
        ['name'=>'New Year', 'slug'=>'new-year', 'type'=>'sub-page'],
        ['name'=>'Makar Sankranti', 'slug'=>'makar-sankranti', 'type'=>'sub-page'],
        ['name'=>'Pongal', 'slug'=>'pongal', 'type'=>'sub-page'],
        ['name'=>'Vasant Panchami', 'slug'=>'vasant-panchami', 'type'=>'sub-page'],
        ['name'=>'Maha Shivratri', 'slug'=>'shivratri', 'type'=>'sub-page'],
        ['name'=>'Holi', 'slug'=>'holi', 'type'=>'sub-page'],
        ['name'=>'Gudi Padwa', 'slug'=>'gudi-padwa', 'type'=>'sub-page'],
        ['name'=>'Ugadi', 'slug'=>'ugadi', 'type'=>'sub-page'],
        ['name'=>'Ram Navami', 'slug'=>'ram-navami', 'type'=>'sub-page'],
        ['name'=>'Mahavir Jayanti', 'slug'=>'mahavir-jayanti', 'type'=>'sub-page'],
        ['name'=>'Hanuman Jayanti', 'slug'=>'hanuman-jayanati', 'type'=>'sub-page'],
        ['name'=>'Good Friday', 'slug'=>'good-friday', 'type'=>'sub-page'],
        ['name'=>'Vaisakhi', 'slug'=>'vaisakhi', 'type'=>'sub-page'],
        ['name'=>'Ramzan or Ramadan', 'slug'=>'ramadan', 'type'=>'sub-page'],
        ['name'=>'Shani Jayanti', 'slug'=>'shani-jayanti', 'type'=>'sub-page'],
        ['name'=>'Guru Purnima', 'slug'=>'guru-purnima', 'type'=>'sub-page'],
        ['name'=>'Hariyali Teej', 'slug'=>'hariyali-teej', 'type'=>'sub-page'],
        ['name'=>'Nag Panchami', 'slug'=>'nag-panchami', 'type'=>'sub-page'],
        ['name'=>'Raksha Bandhan', 'slug'=>'raksha-bandhan', 'type'=>'sub-page'],
        ['name'=>'Sri Krishna Janmashtami', 'slug'=>'janmashtami', 'type'=>'sub-page'],
        ['name'=>'Ganesh Chaturthi', 'slug'=>'ganesh-chaturthi', 'type'=>'sub-page'],
        ['name'=>'Onam', 'slug'=>'onam', 'type'=>'sub-page'],
        ['name'=>'Pitru Paksha', 'slug'=>'pitru-paksha', 'type'=>'sub-page'],
        ['name'=>'Navratri', 'slug'=>'navratri', 'type'=>'sub-page'],
        ['name'=>'Durga Puja', 'slug'=>'durga-puja', 'type'=>'sub-page'],
        ['name'=>'Dussehra', 'slug'=>'dussehra', 'type'=>'sub-page'],
        ['name'=>'Karvachauth', 'slug'=>'karva-chauth', 'type'=>'sub-page'],
    ];
    return $festival;
}

function typeDWM(){
    return [
        'free' => '',
        'love' => 'Love',
        'career' => 'Career',
        'finance' => 'Finance',
        'health' => 'Health'
    ];
}
function typeYear(){
    return [
        'free' => '',
        'love' => 'Love',
        'career' => 'Career',
        'finance' => 'Finance',
        'family' => 'Family'
    ];
}

function punchangAr(){
    $punchang = [
        ['name'=>'Panchang', 'slug'=>'panchang', 'type'=>'page'],
        ['name'=>'Tithi', 'slug'=>'tithi', 'type'=>'sub-page'],
        ['name'=>'Nakshatra', 'slug'=>'nakshatra', 'type'=>'sub-page'],
        ['name'=>'Karna', 'slug'=>'karna', 'type'=>'sub-page'],
        ['name'=>'Yoga', 'slug'=>'yoga', 'type'=>'sub-page'],
        ['name'=>'Ayanamsa', 'slug'=>'ayanamsa', 'type'=>'sub-page'],
        ['name'=>'Rasi', 'slug'=>'rasi', 'type'=>'sub-page'],
        ['name'=>'Rahukaal', 'slug'=>'rahukaal', 'type'=>'sub-page'],
        ['name'=>'Gulika', 'slug'=>'gulika', 'type'=>'sub-page'],
        ['name'=>'Yamakanta', 'slug'=>'yamakanta', 'type'=>'sub-page'],
    ];
    return $punchang;
}

function serviceArr() {
  $serviceArr =[
    ['name' => 'Kundli', 'amount' => '100'],
    ['name' => 'Match Macking', 'amount' => '700'],
    ['name' => 'Vastu', 'amount' => '500'],
  ];
  return $serviceArr;
}

function zodiacSignNameHindiEnglish($key=''){
  $array=[
    'aries'=>'Aries',
    'taurus'=>'Taurus',
    'gemini'=>'Gemini',
    'cancer'=>'Cancer',
    'leo'=>'Leo',
    'virgo'=>'Virgo',
    'libra'=>'Libra',
    'scorpio'=>'Scorpio',
    'sagittarius'=>'Sagittarius',
    'capricorn'=>'Capricorn',
    'aquarius'=>'Aquarius',
    'pisces'=>'Pisces',
    'मेष'=>'Aries',
    'वृषभ'=>'Taurus',
    'मिथुन'=>'Gemini',
    'कर्क'=>'Cancer',
    'सिंह'=>'Leo',
    'कन्या'=>'Virgo',
    'तुला'=>'Libra',
    'वृश्चिक'=>'Scorpio',
    'धनु'=>'Sagittarius',
    'मकर'=>'Capricorn',
    'कुंभ'=>'Aquarius',
    'मीन'=>'Pisces',
  ];
  return $key?($array[$key]??''):$array;
}
