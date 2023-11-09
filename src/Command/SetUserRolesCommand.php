<?php
namespace App\Command;


use App\Repository\UsuarioRepository;
use Symfony\Component\Console\Command\Command; 
use Symfony\Component\Console\Input\InputInterface; 
use Symfony\Component\Console\Output\OutputInterface;


class SetUserRolesCommand extends Command
{
    protected static $defaultName ='app:user:set-roles';
    
    public function __construct(private UsuarioRepository $UsuarioRepository)
    {
        parent::__construct();
    }   
    
   protected function execute(InputInterface $input, OutputInterface $output): int
   {
            $user = $this->UsuarioRepository->findOneByEmail('danieljimplaysix@gmail.com');
            $user->setRoles(['ROLE_ADMIN']);
            $this->UsuarioRepository->add($user); 
             return Command::SUCCESS;   
   }    
}




