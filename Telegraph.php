<?php

//Module-8
class TelegraphText
{
    public string $text, $title, $author, $published, $slug;

    /*
     * @param string $author
     * @param string $slug
     *
     * @return void
     */
    //
    public function __construct(string $author, string $slug) {
        $this->published = date('l jS \of F Y h:i:s A');
        $this->author = $author;
        $this->slug = $slug;
    }

    /*
     * @return void
     */
    public function storeText(): void{
        $arrStoreText = [
            'text' =>  $this->text,
            'title' => $this->title,
            'author' => $this->author,
            'published' => $this->published
        ];
        file_put_contents($this->slug, serialize($arrStoreText));
    }

    /*
     * @return string
     */
    public function loadText(): string {
        if (!file_exists($this->slug) || filesize($this->slug) <= 0) {
            return '';
        }
        $arrLoadText = unserialize(file_get_contents($this->slug));
        $this->text = $arrLoadText['text'];
        $this->title = $arrLoadText['title'];
        $this->author = $arrLoadText['author'];
        $this->published = $arrLoadText['published'];
        return $this->text;
    }

    /*
     * @param string $title
     * @param string $text
     *
     * @return void
     */
    public function editText(string $title, string $text): void {
        $this->text = $text;
        $this->title = $title;
    }
}


//Module-9

abstract class store {
    /*
     * @param string $object
     * @return string $id
     */
    abstract function create (string $object,string $slug);
    /*
    * @param string $id
    * @param string $slug
    * @return string $object
    */
    abstract function reade ();
    /*
    * @param string $id
    * @param string $slug
    * @param string $object
    */
    abstract function update ();
    /*
    * @param string $id
    * @param string $slug
    */
    abstract function delete ();
    // @return string $arrObject
    abstract function list();
}

abstract class View {
    public $storage;

    public function __construct(string $object) {
        $this->storage = $object;
    }
    // @param string $id
    abstract function displayTextById(int $id);
    // @param string $url
    abstract function displayTextByUrl(string $url);
}

class Storage extends View {
    public function __construct($storage) {
        $this->storage = $storage;
    }
    function displayTextById(int $id){

    }
    function displayTextByUrl(string $url){

    }
}
abstract class User {
    public string $id, $name, $role;
    abstract function getTextsToEdit();
}
class FileStorage extends store
{
    public string $object;
    public string $slug;
    function create(string $object,string $slug): void
    {
        $this->object = $object;
        $this->slug = $slug;
        $nameFile = $nameFile2 = $this->slug . date('l jS \of F Y');
        $i = 0;
        while (file_exists($nameFile)){
            $i++;
            $nameFile = $nameFile2 . $i;
            file_put_contents($nameFile, $this->object);
        }
       }

    function reade (){
    }
    function update (){

    }
    function delete (){

    }
    function list(){

    }
}

$author = 'Автор';
$slug = 'test_text_file';

$proba = new TelegraphText($author, $slug);
$proba->text = 'текст';
$proba->title = 'заголовок';

$proba->editText('title2','text2');
$proba->storeText();
print_r($proba->loadText());

print_r($proba); //вывод на экран обьекта класса TelegraphText.
$proba2 = new FileStorage;
$object = serialize(new TelegraphText($author, $slug));
$proba2->create($object, $slug);
print_r($proba2);