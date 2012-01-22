<?php

//some handy features
function Scale10( $var, $base = 4, $zero = '-' )
{
    # we scale 1-4, etc to 1-10
    return ($var > 0)
            ? ( $var - 1 ) * 9 / ( $base - 1 ) + 1
            : $zero;
}