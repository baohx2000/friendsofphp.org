<?php declare(strict_types=1);

namespace Fop\Command;

use Fop\Entity\Group;
use Fop\Importer\GroupsFromPhpUgImporter;
use Fop\Repository\GroupRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class PhpUgImportCommand extends Command
{
    /**
     * @var GroupsFromPhpUgImporter
     */
    private $groupsFromPhpUgImporter;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(
        GroupsFromPhpUgImporter $groupsFromPhpUgImporter,
        GroupRepository $userGroupRepository,
        SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
        $this->groupsFromPhpUgImporter = $groupsFromPhpUgImporter;
        $this->groupRepository = $userGroupRepository;
        $this->symfonyStyle = $symfonyStyle;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Import groups from php.ug.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->symfonyStyle->note('Importing groups from php.ug');
        $this->importsGroups();
        $this->symfonyStyle->success('Done');
    }

    private function importsGroups(): void
    {
        $groupsByContinent = $this->groupsFromPhpUgImporter->import();
        foreach ($groupsByContinent as $groups) {
            /** @var Group $group */
            foreach ($groups as $group) {
                $this->symfonyStyle->note(sprintf('Groups "%s" imported', $group->getName()));
            }
        }
        $this->groupRepository->saveImportToFile($groupsByContinent);
    }
}