<?php

namespace App\Command;

use App\Entity\Coordinate;
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
            ->addArgument('nom', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('prenom', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('pseudo', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('phone', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('address', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('zip', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('city', InputArgument::OPTIONAL, 'Argument description')
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
        $nom = $input->getArgument('nom');
        if(!$nom){
            $question = new Question('Quel est le nom de '. $email. ' ?');
            $nom = $helper->ask($input,$output,$question);
        }
        $prenom = $input->getArgument('prenom');
        if(!$prenom){
            $question = new Question('Quel est le prenom de '. $email. ' ?');
            $prenom = $helper->ask($input,$output,$question);
        }
        $pseudo = $input->getArgument('pseudo');
        if(!$pseudo){
            $question = new Question('Quel est le pseudo de '. $email. ' ?');
            $pseudo = $helper->ask($input,$output,$question);
        }
        $phone = $input->getArgument('phone');
        if(!$phone){
            $question = new Question('Quel est le téléphone de '. $email. ' ?');
            $phone = $helper->ask($input,$output,$question);
        }
        $address = $input->getArgument('address');
        if(!$address){
            $question = new Question('Quelle est l\'adresse de '. $email. ' ?');
            $address = $helper->ask($input,$output,$question);
        }
        $zip = $input->getArgument('zip');
        if(!$zip){
            $question = new Question('Quelle est le code postal de '. $email. ' ?');
            $zip = $helper->ask($input,$output,$question);
        }
        $city = $input->getArgument('city');
        if(!$city){
            $question = new Question('Quelle est l\'adresse de '. $email. ' ?');
            $city = $helper->ask($input,$output,$question);
        }

        $user = (new User())->setEmail($email)
                            ->setPlainPassword($plainPassword)
                            ->setRoles(['ROLE_USER','ROLE_ADMIN'])
                            ->setVerified(true)
                            ->setCreatedAt(new \DateTimeImmutable());
        $this->em->persist($user);
        $this->em->flush();
        $coordinate = (new Coordinate())->setUser($user)
                                        ->setUpdatedAt(new \DateTimeImmutable())
                                        ->setAddress($address)
                                        ->setZip($zip)
                                        ->setCity($city)
                                        ->setPhone($phone)
                                        ->setNom($nom)
                                        ->setPrenom($prenom)
                                        ->setPseudo($pseudo)
                                        ->setCompleted(true);
        $this->em->persist($coordinate);
        $this->em->flush();
        $io->success('The administrator has been create ');

        return Command::SUCCESS;
    }
}
