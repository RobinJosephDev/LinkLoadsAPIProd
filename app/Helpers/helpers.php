<?php

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '₹' . number_format($amount, 2);
    }
}
