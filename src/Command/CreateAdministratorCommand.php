<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:create-administrator',
    description: 'Create an administrator',
)]
class CreateAdministratorCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct('app:create-administrator');
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('password', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input,$output);
        $email = $input->getArgument('email');
        if(!$email){
            $question = new Question('Quel est l\'email de l\'administrateur ?');
            $email = $helper->ask($input,$output,$question);
        }
        $plainPassword = $input->getArgument('password');
        if(!$plainPassword){
            $question = new Question('Quel est le mot de passe de '. $email. ' ?');
            $plainPassword = $helper->ask($input,$output,$question);
        }

        $user = (new User())->setEmail($email)
                            ->setPlainPassword($plainPassword)
                            ->setRoles(['ROLE_USER','ROLE_ADMIN'])
                            ->setVerified(true)
                            ->setCreatedAt(new \DateTimeImmutable());
        $this->em->persist($user);
        $this->em->flush();

        $io->success('The administrator has been create ');

        return Command::SUCCESS;
    }
}
