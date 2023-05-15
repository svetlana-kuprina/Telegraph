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
    public function __construct(string $author, string $slug)
    {
        $this->published = date('l jS \of F Y h:i:s A');
        $this->author = $author;
        $this->slug = $slug;
    }

    /*
     * @return void
     */
    public function storeText(): void
    {
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
        if (!file_exists($this->slug) || filesize($this->slug) <= 0)
        {
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
    public function editText(string $title, string $text): void
    {
        $this->text = $text;
        $this->title = $title;
    }
}


//Module-9

abstract class Storage
{
    /*
     * @param object $telegraphText
     * @param string $slug
     * @return string $id
     */
    abstract function create (object $classTelegraphText,string $slug): string;
    /*
     * @param string $slug
     * @param string $id
     *
     * @return object
     */
    abstract function reade (string $slug = '', string $id = ''): object;
    /*
    * @param object $telegraphText
    * @param string $slug
    * @param string $id
    *
    * @return void
    */
    abstract function update (object $telegraphText, string $slug = '', string $id = ''): void;
    /*
     * @param string $slug
     * @param string $id
     *
     * @return void
     */
    abstract function delete (string $slug = '', string $id = ''): void;
    /*
      * @return array
      */
    abstract function list(): array;
}

abstract class View
{
    /*
     * object $storage
     */
    public $storage;

    public function __construct(string $storage)
    {
        $this->storage = $storage;
    }
    /*
       * @param string $id
       *
       * @return void
       */
    abstract function displayTextById(int $id): void;
    /*
       * @param string $url
       *
       * @return void
       */
    abstract function displayTextByUrl(string $url): void;
}

abstract class User
{
    /*
    * string $id
    * string $name
    * string role
    */
    public string
        $id,
        $name,
        $role;
    /*
     * @return array
     */
    abstract function getTextsToEdit(): array;
}

class FileStorage extends Storage
{
    public function create(object $classTelegraphText, string $slug): string
    {
        // TODO: Implement create() method.
    }

    function reade(string $slug = '', string $id = ''): object
    {
        // TODO: Implement reade() method.
    }

    function update(object $telegraphText, string $slug = '', string $id = ''): void
    {
        // TODO: Implement update() method.
    }

    function delete(string $slug = '', string $id = ''): void
    {
        // TODO: Implement delete() method.
    }

    function list(): array
    {
        // TODO: Implement list() method.
    }
}
