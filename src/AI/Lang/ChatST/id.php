<?php

namespace AI\Lang\ChatST;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 */

class id
{
    /**
     * ['t'][0] = bool $word_match
     * ['t'][1] = int  $max_length
     * ['t'][2] = int  $max_words
     * ['t'][3] = bool $time_reply
     */
    public static $wordlist = [
            "hola,alo" => [
                                "r" => [
                                    "1-10" => [
                                        "Halo juga ^@, selamat pagi :D"
                                    ],
                                    "11-14" => [
                                        "Halo juga ^@, selamat siang.."
                                    ],
                                    "15-18" => [
                                        "Halo juga ^@, selamat sore.."
                                    ],
                                    "19-24" => [
                                        "Halo juga ^@, selamat malam.."
                                    ]
                                ],
                                "t" => [false, 15, 3, true],
                                "words_exception" => [
                                    "balon", "galon",
                                ]
            ],
            'hi,hai,hay,hae' => [
                                'r'=>[
                                    'Hai juga ^@',
                                    'Hai juga ^@, apa kabar?',
                                    'Hai juga ^@, apa kabar, sehat kan?'
                                ],
                                't'=>[true, 10, 3, false]
                            ],
            'pa+kbr,pa+kabar,pa+kabr' => [
                                'r'=>[
                                    'Kabar baik disini.'
                                ],
                                't'=>[false, 35, 7, false]
                            ],
            'jam+brp,jm+brp,jam+berapa,jm+berapa' => [
                                'r'=>[
                                    '0-3' => [
                                        'Sekarang jam #d(jam) dini hari.'
                                    ],
                                    '4-10' => [
                                        'Sekarang jam #d(jam) pagi.'
                                    ],

                                    '12-14' => [
                                        'Sekarang jam #d(jam) siang.'
                                    ],
                                    '15-18' => [
                                        'Sekarang jam #d(jam) sore.'
                                    ],
                                    '19-24' => [
                                        'Sekarang jam #d(jam) malam.'
                                    ]
                                ],
                                't'=>[true, 30, 6, true]
                            ],
            "besok+hari,bsk+hari,besok+hri,bsk+hri" => [
                                "r" => [
                                    "Besok hari #d(day+1day)",
                                ],
                                "t" =>[false, 30, 6, false]
                            ],
            "kemarin+hari,kmrin+hr,kemarin+hr" => [
                                "r" => [
                                    "Kemarin hari #d(day-1day)",
                                ],
                                "t" =>[false, 30, 6, false]
                            ],
            'hari+apa,hr+apa' => [
                                'r'=> [
                                    'Sekarang hari #d(day).'
                                ],
                                't'=>[true, 20, 6, false]
                            ],
            'bulan+apa' => [
                                'r'=>[
                                    'Sekarang bulan #d(month).'
                                ],
                                't'=>[true, 20, 6, false]
                            ],
            "pagi,pagy" => [
                                "r" => [
                                    "1-11" => [
                                        "Selamat pagi ^@, selamat beraktifitas!"
                                    ],
                                    "12-14" => [
                                        "Ini sudah siang ^@, selamat beraktifitas!"
                                    ],
                                    "15-18" => [
                                        "Ini sudah sore ^@, selamat beristirahat!"
                                    ],
                                    "19-24" => [
                                        "Ini sudah malam ^@, selamat beraktifitas!"
                                    ]
                                ],
                                "t"=>[false, 20, 6, true],
                                "words_exception" => [
                                    "ini", "hari", "lain"
                                ]
                            ],
            "siang,ciang,syang,siank" => [
                                "r" => [
                                    "1-10" => [
                                        "Ini masih pagi ^@, selamat beraktifitas!"
                                    ],
                                    "11-14" => [
                                        "Selamat siang ^@, selamat beraktifitas!"
                                    ],
                                    "15-18" => [
                                        "Ini udah sore ^@, selamat beristirahat!"
                                    ],
                                    "19-24" => [
                                        "Ini udah malam ^@, selamat beristirahat!",
                                    ]
                                ],
                                "t"=>[false, 20, 6, true],
                                "words_exception" => [
                                    "ini", "hari", "lain"
                                ]
                            ],
            "sore" => [
                                "r" => [
                                    "1-10" => [
                                        "Ini masih pagi ^@, selamat beraktifitas!"
                                    ],
                                    "11-14" => [
                                        "Ini masih siang ^@, selamat beraktifitas!"
                                    ],
                                    "15-18" => [
                                        "Selamat sore ^@, selamat beristirahat!"
                                    ],
                                    "19-24" => [
                                        "Ini udah malam ^@, selamat beristirahat!"
                                    ]
                                ],
                                "t" =>[false, 20, 6, true],
                                "words_exception" => [
                                    "ini", "hari", "lain"
                                ]
                            ],
            "malam,malem" => [
                                "r" => [
                                    "1-3" => [
                                        "Ini udah pagi ^@, selamat beraktifitas!"
                                    ],
                                    "4-10" => [
                                        "Ini masih pagi ^@, selamat beraktifitas!"
                                    ],
                                    "11-14" => [
                                        "Ini masih siang ^@, selamat beraktifitas!"
                                    ],
                                    "15-18" => [
                                        "Ini masih sore ^@!",
                                    ],
                                    "19-24" => [
                                        "Selamat malam ^@, selamat beristirahat!"
                                    ]
                                ],
                                "t" => [false, 20, 6, true],
                                "words_exception" => [
                                    "ini", "hari", "lain"
                                ]
                            ],
            "ohayo" => [
                                "r" => [
                                    "1-11" => [
                                        "Ohayou ^@, selamat beraktifitas!"
                                    ],
                                    "12-14" => [
                                        "Ini sudah siang ^@, selamat beraktifitas!"
                                    ],
                                    "15-18" => [
                                        "Ini sudah sore ^@, selamat beristirahat!"
                                    ],
                                    "19-24" => [
                                        "Ini sudah malam ^@, selamat beraktifitas!"
                                    ]
                                ],
                                "t"=>[false, 20, 6, true]
                            ],
            "koniciwa,konnichiwa,konniciwa,konichiwa" => [
                                "r" => [
                                    "1-9" => [
                                        "Ini masih pagi ^@, selamat beraktifitas!"
                                    ],
                                    "10-14" => [
                                        "Konniciwa ^@, selamat beraktifitas!"
                                    ],
                                    "15-18" => [
                                        "Konniciwa ^@, selamat beristirahat!"
                                    ],
                                    "19-24" => [
                                        "Ini udah malam ^@, selamat beristirahat!",
                                    ]
                                ],
                                "t"=>[false, 20, 6, true],
                            ],
            "konbanwa,konbawa,kobawa" => [
                                "r" => [
                                    "1-3" => [
                                        "Ini masih pagi ^@, selamat beraktifitas!"
                                    ],
                                    "4-10" => [
                                        "Ini masih pagi ^@, selamat beraktifitas!"
                                    ],
                                    "11-14" => [
                                        "Konbanwa ^@, selamat beraktifitas!"
                                    ],
                                    "15-24" => [
                                        "Konbanwa ^@, selamat beristirahat!",
                                    ]
                                ],
                                "t" => [false, 20, 6, true]
                            ],
            "sehat,baik" => [
                                "r" => [
                                    "Syukurlah.",
                                    "Baguslah.",
                                    "Senang mendengarnya."
                                ],
                                "t" => [true, 10, 3, false]
            ],
            "haha,wkwk,hihi,xixi,hhhh" => [
                                "r" => [
                                    "Hahaha, ketawa :v",
                                    "Dilarang ketawa!"
                                ],
                                "t" => [false, 45, 10, false]
                            ],
            "makan+apa" => [
                                "r" => [
                                    "Makan gamping",
                                    "Makan kamu",
                                ],
                                "t" => [false, 15, 10, false]
            ],
            "makan" => [
                                "r" => [
                                    "Yuk makan",
                                    "Pernah makan gamping?"
                                ],
                                "t" => [false, 15, 10, false]
            ],
            "laravel" => [
                                "r" => [
                                    "Wah laravel, kok kayak nama framework yak."
                                ],
                                "t" => [true, 10, 3, false]
            ],
            "code,coding,ngoding" => [
                                "r" => [
                                    "Yuk ngoding",
                                    "Ngoding emang asik"
                                ],
                                "t" => [true, 10, 3, false]
            ],
            "presiden+kita,presiden+ri" => [
                                "r" => [
                                    "Joko Widodo"
                                ],
                                "t" => [true, 35, 5, false]
            ],
            "laper,lapar" => [
                                "r" => [
                                    "Kalau lapar ya makan :p"
                                ],
                                "t" => [true, 12, 3, false]
            ]
    ];
}
