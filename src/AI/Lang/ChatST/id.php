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
            "hi,hai,hay,hae" => [
                                "r"=>[
                                    "Hai juga ^@",
                                    "Hai juga ^@, apa kabar?"
                                ],
                                "t"=>[true, 10, 3, false]
                            ],
            "pa+kbr,pa+kabar,pa+kabr" => [
                                "r"=>[
                                    "Kabar baik disini."
                                ],
                                "t"=>[false, 35, 7, false]
                            ],
            "jam+brp,jm+brp,jam+berapa,jm+berapa" => [
                                "r"=>[
                                    "0-3" => [
                                        "Sekarang jam #d(jam) dini hari."
                                    ],
                                    "4-11" => [
                                        "Sekarang jam #d(jam) pagi."
                                    ],
                                    "12-14" => [
                                        "Sekarang jam #d(jam) siang."
                                    ],
                                    "15-18" => [
                                        "Sekarang jam #d(jam) sore."
                                    ],
                                    "19-24" => [
                                        "Sekarang jam #d(jam) malam."
                                    ]
                                ],
                                "t"=>[true, 30, 6, true]
                            ],
            "hari+apa,hr+apa" => [
                                "r"=> [
                                    "Sekarang hari #d(day)."
                                ],
                                "t"=>[true, 20, 6, false]
                            ],
            "bulan+apa" => [
                                "r"=>[
                                    "Sekarang bulan #d(month)."
                                ],
                                "t"=>[true, 20, 6, false]
            ]
    ];
}