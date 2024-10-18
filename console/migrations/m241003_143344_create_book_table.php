<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book}}`.
 */
class m241003_143344_create_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создаем таблицу книга
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(256),
            'year'  => $this->smallInteger()->unsigned(),
            'description' => $this->text(),
            'isbn'  => $this->string(32),
            'image' => $this->string(256),
        ]);

        // Таблица "Автор"
        $this->createTable('{{%author}}', [
            'id'            => $this->primaryKey(),
            'first_name'    => $this->string(64),
            'last_name'     => $this->string(64),
            'third_name'    => $this->string(64),
        ]);

        // Связующая для книги и автора
        $this->createTable('{{%book_to_author}}', [
            'id'        => $this->primaryKey(),
            'book_id'   => $this->integer(),
            'author_id' => $this->integer(),
        ]);

        // Таблица подписок пользователя
        $this->createTable('{{%subscription}}', [
            'id'        => $this->primaryKey(),
            'phone'     => $this->string(15),
            'author_id' => $this->integer(),
        ]);

        // Заполнение примерами данных
        $this->fillTables();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%subscription}}');
        $this->dropTable('{{%book_to_author}}');
        $this->dropTable('{{%author}}');
        $this->dropTable('{{%book}}');
    }

    private function fillTables()
    {
        $bookCols = ['title', 'year', 'description', 'isbn', 'image'];

        $books = [
            array_combine($bookCols, [
                'Ведьмак. Последнее желание',
                1986,
                '',
                '5-7921-0081-0',
                'https://upload.wikimedia.org/wikipedia/ru/9/9a/Ostatnie_%C5%BCyczenie.jpg',
            ]),
            array_combine($bookCols, [
                'Ведьмак. Меч предназначения',
                1992,
                '',
                '5-7921-0081-2',
                'https://upload.wikimedia.org/wikipedia/commons/8/87/Geralt.jpg',
            ]),
            array_combine($bookCols, [
                'Ведьмак. Кровь эльфов',
                1994,
                '',
                '5-7921-0081-3',
                'https://upload.wikimedia.org/wikipedia/commons/8/87/Geralt.jpg',
            ]),
            array_combine($bookCols, [
                'Ведьмак. Час презрения',
                1995,
                '',
                '5-7921-0081-6',
                'https://upload.wikimedia.org/wikipedia/commons/8/87/Geralt.jpg',
            ]),
            array_combine($bookCols, ['Математика. 6 класс', 2013, '', '', '']),
        ];

        $this->batchInsert('book', $bookCols, $books);

        $authorCols = ['first_name', 'last_name', 'third_name'];

        $authors = [
            array_combine($authorCols, ['Анджей', 'Сапковский', '']),
            array_combine($authorCols, ['Аркадий', 'Мерзляк', '']),
            array_combine($authorCols, ['Виталий', 'Полонский', '']),
        ];

        $this->batchInsert('author', $authorCols, $authors);

        $this->batchInsert('book_to_author', [
            'book_id',
            'author_id',
        ], [[1, 1], [2, 1], [3, 1], [4, 1], [5, 2], [5, 3]]);

        $this->batchInsert('subscription', [
            'phone',
            'author_id',
        ], [['79008003050', 1]]);
    }
}
