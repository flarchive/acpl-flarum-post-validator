<?php

namespace ACPL\PostValidator\Console;

use Exception;
use Flarum\Console\AbstractCommand;
use Flarum\Http\UrlGenerator;
use Flarum\Post\CommentPost;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Output\ConsoleOutput;

class ValidatePosts extends AbstractCommand
{
    private UrlGenerator $url;
    private Filesystem $uploadDir;

    public function __construct(UrlGenerator $url, Factory $filesystemFactory)
    {
        parent::__construct();
        $this->url = $url;
        $this->uploadDir = $filesystemFactory->disk('flarum-assets');
    }

    protected function configure()
    {
        $this
            ->setName('validate-posts')
            ->setDescription('Validates all posts in the database')
            ->addOption('chunk', null, InputOption::VALUE_REQUIRED);
    }

    protected function fire()
    {
        $query = CommentPost::query()->where('type', '=', 'comment');

        $progressBar = new ProgressBar(new ConsoleOutput(), $query->count());
        $progressBar->setFormat('very_verbose');
        $progressBar->setMessage('Validating all posts. This may take a while.');
        $progressBar->start();

        $invalid = [];
        $query->chunk($this->input->getOption('chunk') ?? 100,
            function ($posts) use (&$invalid, &$progressBar) {
                foreach ($posts as $post) {
                    try {
                        $post->formatContent();
                    } catch (Exception $e) {
                        $invalid[] = [
                            'id' => $post->id,
                            'url' => $this->url->to('forum')->route('discussion',
                                ['id' => $post->discussion_id, 'near' => $post->number]),
                        ];
                    }
                    $progressBar->advance();
                }
            });

        $progressBar->finish();
        $this->info("\n".count($invalid).' invalid posts found');

        if (count($invalid)) {
            $this->uploadDir->makeDirectory('invalid-posts');
            $fileName = 'invalid-posts/invalid-posts-'.time().'.json';
            $this->uploadDir->put($fileName, json_encode($invalid));

            $this->info('List was saved to: /public/assets/'.$fileName);
        }
    }
}
