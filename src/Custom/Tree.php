<?php

namespace App\Custom;

class Tree
{
    function generate (Int $size): string
    {
        if ($size < 2) {
            return "Sorry, it's impossible build a tree with less than 2 lines.";
        }

        //put numbers of stars per line in array
        $starsForLine = [];
        for ($i = 0; $i < $size; $i++) {
            if ($i == 0) {
                $starsForLine[] = 1;
                continue;
            }
            $starsForLine[] = 2 * $i + 1;

        }

        $maxBottomStars = $starsForLine[count($starsForLine) - 1];
        $treeOutput = "";
        for ($j = 0, $jj = ($size - 1); $j < $size; $j++, $jj--) {

            //check if is last line
            if ($j == ($size - 1)) {
                $treeOutput .= str_repeat("*", $maxBottomStars) . "<br/>";
                break;
            }

            $lineOutput = str_repeat(" ", $maxBottomStars);
            $partialStars = str_repeat("*", $starsForLine[$j]);
            $startingPosition = $jj;

            //put line stars on current line output
            $treeOutput .= substr_replace($lineOutput, $partialStars, $startingPosition) . "<br/>";
        }


        return $treeOutput;
    }
}