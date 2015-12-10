# Google Search Suggestions
This PHP class will return 10 Google Search suggestions for any given language and phrase(s)

## Usage:
Very difficult.

```php 
<?php
include 'Suggest.php';
$suggest = new Suggest('where do i ', 'en');
print_r($suggest->data);
?>
```

Result:
<pre>
Array
(
    [0] = where do i live
    [1] = where do i register to vote
    [2] = where do i vote
    [3] = where do i get a passport
    [4] = where do i go from here
    [5] = where do i want to eat
    [6] = where do i begin
    [7] = where do i get a money order
    [8] = where do i apply for a passport
    [9] = where do i belong
)
</pre>

It will return cached data if a cache file for the search query / language combination already exists in the <i>Cache</i> folder. Make sure that the directory is writeable.

