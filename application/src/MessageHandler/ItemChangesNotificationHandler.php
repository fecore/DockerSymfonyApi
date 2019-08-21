<?php
namespace App\MessageHandler;

use App\Message\ItemChangesNotification;
use Hoa\Exception\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ItemChangesNotificationHandler implements MessageHandlerInterface
{

    private $filesystem;
    private $parameterBag;

    public function __construct(Filesystem $filesystem, ContainerBagInterface $parameterBag)
    {
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
    }

    public function __invoke(ItemChangesNotification $itemChangesNotification)
    {

        // ItemUpdating log
        // With only active items

        try {
            switch ($itemChangesNotification->method) {
                case "update":
                    $this->filesystem->appendToFile(
                        $this->parameterBag->get('kernel.project_dir') . '/storage/items.txt',
                        "{$itemChangesNotification->id}\t{$itemChangesNotification->name}\t{$itemChangesNotification->updatedAt}\n"
                    );
                    break;
                case "delete":
                    // Delete from file
                    $content = file_get_contents ($this->parameterBag->get('kernel.project_dir') . '/storage/items.txt');

                    // Find by id and remove
                    $output = preg_replace( '#^' . $itemChangesNotification->id . '[\t].*$#m', '', $content);
                    $output = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $output);

                    $this->filesystem->dumpFile($this->parameterBag->get('kernel.project_dir') . '/storage/items.txt', $output);
                    break;
            }
        } catch (\Exception $e) {

            // I know not a implementation of PSR
            // But I'm to tired right know
            // Sorry for that :´-(

            $this->filesystem->appendToFile(
                $this->parameterBag->get('kernel.project_dir') . '/storage/errors.txt',
                $e->getMessage() . "     |     "  .  $e->getLine()
            );
        }


    }
}