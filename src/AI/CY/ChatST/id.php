<?php

namespace AI\CY\ChatST;

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
                                "t"=>[false, 25, 4, false]
                            ],
    ];
}
