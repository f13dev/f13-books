<?php namespace F13\Books\Views;

class Books
{
    public $label_amazon;
    public $label_contributors;
    public $label_first_sentence;
    public $label_goodreads;
    public $label_google_books;
    public $label_isbn;
    public $label_librarything;
    public $label_links;
    public $label_pages;
    public $label_paperback_swap;
    public $label_published;
    public $label_publisher;

    public function __construct($params = array())
    {
        foreach ($params as $k => $v)
        {
            $this->{$k} = $v;
        }

        $this->label_amazon = __('Amazon', 'f13-books');
        $this->label_contributors = __('Contributors', 'f13-books');
        $this->label_first_sentence = __('First sentence', 'f13-books');
        $this->label_goodreads = __('Goodreads', 'f13-books');
        $this->label_google_books = __('Google Books', 'f13-books');
        $this->label_isbn = __('ISBN', 'f13-books');
        $this->label_librarything = __('LibraryThing', 'f13-books');
        $this->label_links = __('Links', 'f13-books');
        $this->label_pages = __('Pages', 'f13-books');
        $this->label_paperback_swap = __('PaperBack Swap', 'f13-books');
        $this->label_published = __('Published', 'f13-books');
        $this->label_publisher = __('Publisher', 'f13-books');
    }

    public function book()
    {
        $v = '<div class="f13-book-container">';

            $v .= '<div class="f13-book-title">';
                $v .= esc_attr($this->data->title);
            $v .= '</div>';

            if (property_exists($this->data, 'first_sentence') && property_exists($this->data->first_sentence, 'value')) {
                $v .= '<div class="f13-books-sentence">';
                    $v .= '<strong>'.$this->label_first_sentence.':</strong> '.esc_attr($this->data->first_sentence->value);
                $v .= '</div>';
            }

            $v .= '<div class="f13-book-body">';
                if (property_exists($this->data, 'cover')) {
                    $v .= '<div class="f13-book-cover">';
                        $v .= '<img src="'.esc_attr($this->data->cover).'" alt="Book cover: '.esc_attr($this->data->title).'">';
                    $v .= '</div>';
                }

                $v .= '<div class="f13-book-info">';
                    if (property_exists($this->data, 'number_of_pages')) {
                        $v .= '<div class="f13-book-meta">';
                            $v .= '<strong>'.$this->label_pages.':</strong> '.esc_attr($this->data->number_of_pages);
                        $v .= '</div>';
                    }

                    if (property_exists($this->data, 'publish_date')) {
                        $v .= '<div class="f13-book-meta">';
                            $v .= '<strong>'.$this->label_published.':</strong> '.esc_attr($this->data->publish_date);
                        $v .= '</div>';
                    }

                    if (property_exists($this->data, 'publishers') && is_array($this->data->publishers)) {
                        $v .= '<div class="f13-book-publisher">';
                            $v .= '<strong>'.$this->label_publisher.':</strong> '.esc_attr(implode(', ', $this->data->publishers));
                        $v .= '</div>';
                    }

                    if (property_exists($this->data, 'isbn_10') && is_array($this->data->isbn_10)) {
                        $v .= '<div class="f13-book-meta">';
                            $v .= '<strong>'.$this->label_isbn.' 10:</strong> '.esc_attr(implode(', ', $this->data->isbn_10));
                        $v .= '</div>';
                    }

                    if (property_exists($this->data, 'isbn_13') && is_array($this->data->isbn_13)) {
                        $v .= '<div class="f13-book-meta">';
                            $v .= '<strong>'.$this->label_isbn.' 13:</strong> '.esc_attr(implode(', ', $this->data->isbn_13));
                        $v .= '</div>';
                    }

                    if (property_exists($this->data, 'contributions') && is_array($this->data->contributions)) {
                        $v .= '<div class="f13-book-meta">';
                            $v .= '<strong>'.$this->label_contributors.':</strong> '.esc_attr(implode(', ', $this->data->contributions));
                        $v .= '</div>';
                    }

                    if (property_exists($this->data, 'identifiers') && !empty($this->data->identifiers)) {
                        $v .= '<div class="f13-book-links">';
                            $v .= '<strong>'.$this->label_links.'</strong>';
                            foreach ($this->data->identifiers as $service => $values) {
                                if (is_array($values) && !empty($values)) {
                                    switch ($service) {
                                        case 'goodreads':
                                            $v .= '<a href="https://www.goodreads.com/book/show/'.esc_attr($values[0]).'" target="_blank" title="More information on Good Reads">';
                                                $v .= $this->label_goodreads;
                                            $v .= '</a>';
                                        break;
                                        case 'librarything':
                                            $v .= '<a href="https://www.librarything.com/work/'.esc_attr($values[0]).'" target="_blank" title="More information on LibraryThing">';
                                                $v .= $this->label_librarything;
                                            $v .= '</a>';
                                        break;
                                        case 'amazon':
                                            $v .= '<a href="https://www.amazon.com/dp/'.esc_attr($values[0]).'" target="_blank" title="More information on Amazon">';
                                                $v .= $this->label_amazon;
                                            $v .= '</a>';
                                        break;
                                        case 'paperback_swap':
                                            $v .= '<a href="https://www.paperbackswap.com/'.urlencode(esc_attr($this->data->title)).'/book/'.esc_attr($values[0]).'" target="_blank" title="More information on PaperBack Swap">';
                                                $v .= $this->label_paperback_swap;
                                            $v .= '</a>';
                                        break;
                                        case 'google':
                                            $v .= '<a href="https://books.google.co.uk/books?id='.esc_attr($values[0]).'" target="_blank" title="More information on Google Books">';
                                                $v .= $this->label_google_books;
                                            $v .= '</a>';
                                        break;
                                    }
                                }
                            }
                        $v .= '</div>';
                    }
                $v .= '</div>';

            $v .= '</div>';

        $v .= '</div>';

        return $v;
    }
}