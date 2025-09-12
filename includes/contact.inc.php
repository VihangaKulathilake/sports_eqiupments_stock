<?php
    // Sample reviews. 
    $reviews = [
        [
            'author' => 'A. Smith',
            'rating' => 5,
            'comment' => 'Great experience! The sports products are high quality and the delivery was fast. I would definitely recommend this store to my friends.',
        ],
        [
            'author' => 'B. Johnson',
            'rating' => 4,
            'comment' => 'Good selection of products. The customer service was helpful when I had a question about my order. The website is easy to use.',
        ],
        [
            'author' => 'C. Williams',
            'rating' => 5,
            'comment' => 'Fantastic! Everything I ordered was exactly as described and arrived on time. The quality of the sports equipment is top-notch.',
        ],
        [
            'author' => 'D. Brown',
            'rating' => 3,
            'comment' => 'The product was good, but it took a little longer than expected to ship. Overall, a decent experience.',
        ],
        [
            'author' => 'E. Davis',
            'rating' => 5,
            'comment' => 'The best place for sports gear. The prices are competitive and the website is very easy to navigate. Will shop here again.',
        ],
    ];

    /**
     * Generates a star rating HTML string based on a given rating.
     *
     * @param int $rating The rating from 1 to 5.
     * @return string HTML string of stars.
     */
    function getStarRating($rating) {
        $output = '';
        for ($i = 0; $i < 5; $i++) {
            if ($i < $rating) {
                $output .= '<span class="star filled">&#9733;</span>';
            } else {
                $output .= '<span class="star">&#9734;</span>';
            }
        }
        return $output;
    }
?>