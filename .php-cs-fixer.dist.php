<?php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

$header = <<<EOF
@copyright 2024 Crehler Sp. z o. o.
 
https://crehler.com/
support@crehler.com
 
This file is part of the PayU plugin for Shopware 6.
License CC BY-NC-ND 4.0 (https://creativecommons.org/licenses/by-nc-nd/4.0/deed.pl) see LICENSE file.

EOF;
$header = str_replace('#year#', (new \DateTime())->format("Y"), $header);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR2' => true,
    '@Symfony' => true,
    'header_comment' => ['header' => $header, 'separate' => 'bottom', 'comment_type' => 'PHPDoc'],
    'no_useless_else' => true,
    'no_useless_return' => true,
    'ordered_class_elements' => true,
    'ordered_imports' => true,
    'phpdoc_order' => true,
    'phpdoc_summary' => false,
    'blank_line_after_opening_tag' => false,
    'concat_space' => ['spacing' => 'one'],
    'array_syntax' => ['syntax' => 'short'],
    'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
])
    ->setFinder($finder);