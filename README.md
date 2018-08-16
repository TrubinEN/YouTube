# YouTube get video info
This is simple class for youtube
```
<?php

require_once ('vendor/autoload.php');

$code = 'bjfQDrfJtuw';
$youtube = new TrubinEN\getInfoYouTube($code);

var_dump($youtube->getTitle(), $youtube->getDate());

```