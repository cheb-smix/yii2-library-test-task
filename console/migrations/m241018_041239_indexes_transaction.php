<?php

use yii\db\Migration;

use common\models\Book;
use yii\db\Expression;

/**
 * Class m241018_041239_indexes_transaction
 */
class m241018_041239_indexes_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
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

        // Внешний ключ (при удалении автора - подписки на него удаляются)
        $this->addForeignKey(
            'fk-subscription-author_id',
            'subscription',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );

        // Простой неуникальный индекс на столбец "год" таблицы книг
        $this->createIndex("book-year-idx", "{{%book}}", "year", false);

        // Составной уникальный индекс на связующую таблицу Книга-Автор
        $this->createIndex("book_to_author-idx-unique", "{{%book_to_author}}", ["book_id", "author_id"], true);

        // Составной уникальный индекс на поля номера телефона и ID автора таблицы подписок
        $this->createIndex("subscription-idx-unique", "{{%subscription}}", ["phone", "author_id"], true);

        // FULLTEXT индекс для поиска по названию и содержанию книги
        $this->execute("ALTER TABLE book ADD FULLTEXT INDEX `book-fulltext-idx` (`title` ASC, `description` ASC)");

        print_r($this->db->createCommand("SHOW INDEXES FROM book")->queryAll());
        print_r($this->db->createCommand("SHOW INDEXES FROM book_to_author")->queryAll());
        print_r($this->db->createCommand("SHOW INDEXES FROM subscription")->queryAll());

        // Пример поиска с использованием FULLTEXT индекса
        $bookSearch = Book::find()->where(new Expression("MATCH (`title`, `description`) AGAINST ('ведьм*' IN BOOLEAN MODE)"));

        print("\n" . $bookSearch->createCommand()->getRawSql() . "\n");

        print_r($bookSearch->asArray()->all());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-subscription-author_id', 'subscription');
        $this->dropForeignKey('fk-book_to_author-author_id', 'book_to_author');
        $this->dropForeignKey('fk-book_to_author-book_id', 'book_to_author');
        $this->dropIndex("subscription-idx-unique", "{{%subscription}}");
        $this->dropIndex("book_to_author-idx-unique", "{{%book_to_author}}");
        $this->dropIndex("book-year-idx", "{{%book}}");
        $this->dropIndex("book-fulltext-idx", "{{%book}}");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241018_041239_indexes_transaction cannot be reverted.\n";

        return false;
    }
    */
}
