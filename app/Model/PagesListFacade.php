<?php

namespace App\Model;

use Nette\Database\Connection;

final class PagesListFacade
{
    protected Connection $db_pages;
    private $check_table;

    public function __construct(private Connection $pages_sqlite)
    {
        $this->check_table = $pages_sqlite->fetch('SELECT count(*) FROM sqlite_master WHERE type=? AND name=?', 'table', 'pages');
        if ($this->check_table['count(*)'] == 0) {
            $pages_sqlite->transaction(function ($pages_sqlite) {
                $pages_sqlite->query('CREATE TABLE IF NOT EXISTS  pages(
                "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
                "alias" VARCHAR(100) UNIQUE NOT NULL,
                "route" VARCHAR(100),
                "path" VARCHAR(100), 
                "title" VARCHAR(100), 
                "description" VARCHAR(255), 
                "keywords" VARCHAR(500), 
                "robots" VARCHAR(100), 
                "img" TEXT, 
                "content" TEXT, 
                "published" INTEGER,
                "access_level" VARCHAR(10), 
                "admin" INTEGER)'
                );
            });
        }
        $this->db_pages = $pages_sqlite;
    }

    public function getPagesData()
    {
        if ($this->check_table['count(*)'] > 0) {
            $check_empty = $this->db_pages->fetchField('SELECT EXISTS(SELECT 1 FROM pages LIMIT ?);', 3);
            if ($check_empty > 0) {
                $data = $this->db_pages->query('SELECT * FROM pages WHERE published=? ORDER BY ?', true, 'DESC');
                foreach ($data as $key => $value) {
                    foreach ($value as $k => $val) {
                        $db_data[$key][$k] = $val;
                    }
                }
            }
        }

        return !empty($db_data) ? $db_data : [];
    }
}
