<?php

use yii\db\Migration;
use common\models\User;

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

        // И индекс на столбец "год"
        $this->createIndex('book-year-idx', '{{%book}}', 'year');

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

        // Уникальность записей
        $this->createIndex('book_to_author-idx-unique', '{{%book_to_author}}', ['book_id', 'author_id'], true);

        // Внешний ключ (при удалении книги - записи, связанные с этой книгой, из таблицы book_to_author затираем)
        $this->addForeignKey(
            'fk-book_to_author-book_id',
            'book_to_author',
            'book_id',
            'book',
            'id',
            'CASCADE'
        );

        // Внешний ключ (при удалении автора - записи, связанные с этим автором, из таблицы book_to_author затираем)
        $this->addForeignKey(
            'fk-book_to_author-author_id',
            'book_to_author',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );

        // Таблица подписок пользователя
        $this->createTable('{{%subscription}}', [
            'id'        => $this->primaryKey(),
            'phone'     => $this->string(15),
            'author_id' => $this->integer(),
        ]);

        // Внешний ключ (при удалении автора - подписки на него удаляются)
        $this->addForeignKey(
            'fk-subscription-author_id',
            'subscription',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );

        // Заполнение примерами данных
        $this->fillTables();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-subscription-author_id', 'subscription');

        $this->dropTable('{{%subscription}}');

        $this->dropForeignKey('fk-book_to_author-author_id', 'book_to_author');
        $this->dropForeignKey('fk-book_to_author-book_id', 'book_to_author');

        $this->dropTable('{{%book_to_author}}');
        $this->dropTable('{{%author}}');

        $this->dropIndex('book-year-idx', '{{%book}}');

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
