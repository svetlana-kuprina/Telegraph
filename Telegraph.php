<?php

class TelegraphText
{
    public $text; //текст
    public $title; //заголовок текста
    public $author; //имя автора
    public $published; // дата и время последнего изменения текста
    public $slug; //уникальное имя файла, в котором будет храниться текст

    public function __construct($author, $slug)
    {
        $this->published=date('l jS \of F Y h:i:s A');
        $this->author = $author;
        $this->slug = $slug;
    }
public function storeText(){
    $arrStoreText = [
        'text'=>  $this->text,
        'title'=> $this->title,
        'author'=>$this->author,
       'published' => $this->published];
    file_put_contents($this->slug,serialize($arrStoreText));
}
public function loadText(){
    if (filesize($this->slug)){
        $arrLoadText = unserialize(file_get_contents($this->slug));
        $this->text = $arrLoadText['text'];
        $this->title = $arrLoadText['title'];
        $this->author = $arrLoadText['author'];
        $this->published = $arrLoadText['published'];
        }
    return $this->text;
    }
public function editText(string $title, string $text){
    $this->text = $text;
    $this->title = $title;
}
}
$author = 'Автор';
$slug = 'test_text_file';

$proba = new TelegraphText($author, $slug);
$proba ->text = 'текст';
$proba ->title = 'заголовок';

$proba->editText('title2','text2');
$proba->storeText();
print_r($proba->loadText());
