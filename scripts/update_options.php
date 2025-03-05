<?php

$src = file_get_contents('https://raw.githubusercontent.com/percona/percona-toolkit/refs/heads/3.x/bin/pt-online-schema-change');

$src = explode('=over', explode('=head1 OPTIONS', $src)[1], 2)[1];

$matches = [];
preg_match_all('/=item --(?<option>[^\n]*)/', $src, $matches);

$options = [];

foreach ($matches['option'] as $o) {
    if (strpos($o, '[no]') === 0) {
        $options[] = str_replace('[no]', '', $o);
        $options[] = str_replace('[no]', 'no-', $o);
    } else {
        $options[] = $o;
    }
}

foreach ($options as $option) {
    if (in_array($option, ['alter', 'execute', 'dry-run'])) continue;
    $constName = mb_strtoupper(str_replace('-', '_', $option));
    echo "public const {$constName} = '{$option}';\n";
}

echo "\n\n\n-----------------------------------------\n\n\n";

$matches = [];
preg_match_all('/\n=item \* (?<shorthand>.*)\n\ndsn: (?<option>[a-z_]*)/', $src, $matches);

$dsnOptions = array_combine($matches['option'], $matches['shorthand']);
foreach ($dsnOptions as $option => $shorthand) {
    //if (in_array($o, ['alter', 'execute', 'dry-run'])) continue;
    $constName = mb_strtoupper(str_replace('-', '_', $option));
    echo "public const {$constName} = '{$shorthand}';\n";
}

print_r($dsnOptions);