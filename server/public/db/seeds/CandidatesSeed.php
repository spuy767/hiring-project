<?php

use Phinx\Seed\AbstractSeed;

class CandidatesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
      $data = [
        [
          'name'    => 'Frank',
          'age' => 50
        ],[
          'name'    => 'Karen',
          'age' => 20
        ]
      ];

      $posts = $this->table('candidates');
      $posts->insert($data)
            ->saveData();
    }
}
