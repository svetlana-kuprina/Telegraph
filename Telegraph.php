<?php

class TelegraphText
{
    public string $text, $title, $author, $published, $slug;

    /*
     * @param string $author
     * @param string $slug
     *
     * @return void
     */
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
$author = 'Автор';
$slug = 'test_text_file';

$proba = new TelegraphText($author, $slug);
$proba->text = 'текст';
$proba->title = 'заголовок';

$proba->editText('title2','text2');
$proba->storeText();
print_r($proba->loadText());
