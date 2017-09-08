<?php
function LevenshteinDistance($s1, $s2)
{
  $sLeft = (strlen($s1) > strlen($s2)) ? $s1 : $s2;
  $sRight = (strlen($s1) > strlen($s2)) ? $s2 : $s1;
  $nLeftLength = strlen($sLeft);
  $nRightLength = strlen($sRight);
  if ($nLeftLength == 0)
    return $nRightLength;
  else if ($nRightLength == 0)
    return $nLeftLength;
  else if ($sLeft === $sRight)
    return 0;
  else if (($nLeftLength < $nRightLength) && (strpos($sRight, $sLeft) !== FALSE))
    return $nRightLength - $nLeftLength;
  else if (($nRightLength < $nLeftLength) && (strpos($sLeft, $sRight) !== FALSE))
    return $nLeftLength - $nRightLength;
  else {
    $nsDistance = range(1, $nRightLength + 1);
    for ($nLeftPos = 1; $nLeftPos <= $nLeftLength; ++$nLeftPos)
    {
      $cLeft = $sLeft[$nLeftPos - 1];
      $nDiagonal = $nLeftPos - 1;
      $nsDistance[0] = $nLeftPos;
      for ($nRightPos = 1; $nRightPos <= $nRightLength; ++$nRightPos)
      {
        $cRight = $sRight[$nRightPos - 1];
        $nCost = ($cRight == $cLeft) ? 0 : 1;
        $nNewDiagonal = $nsDistance[$nRightPos];
        $nsDistance[$nRightPos] =
          min($nsDistance[$nRightPos] + 1,
              $nsDistance[$nRightPos - 1] + 1,
              $nDiagonal + $nCost);
        $nDiagonal = $nNewDiagonal;
      }
    }
    return $nsDistance[$nRightLength];
  }
}