<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Form\Type;

use Optime\Email\Bundle\Entity\EmailAppInterface;
use Optime\Email\Bundle\Entity\EmailLogStatus;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Manuel Aguirre
 */
class EmailLogFilterFormType extends AbstractType
{
    public function __construct(
        private EmailAppProvider $emailAppProvider
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (1 < $this->emailAppProvider->count()) {
            $builder->add('app', EntityType::class, [
                'class' => EmailAppInterface::class,
                'required' => false,
                'placeholder' => '- Select -',
                'multiple' => true,
                'expanded' => true,
            ]);
        }
        $builder->add('config', EntityType::class, [
            'class' => EmailMaster::class,
            'required' => false,
            'placeholder' => '- Select -',
            'multiple' => true,
            'expanded' => true,
        ]);
        $builder->add('recipient', TextType::class, [
            'required' => false,
        ]);
        $builder->add('subject', TextType::class, [
            'required' => false,
        ]);
        $builder->add('log_id', TextType::class, [
            'required' => false,
        ]);
        $builder->add('send_at', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
        ]);
        $builder->add('status', EnumType::class, [
            'class' => EmailLogStatus::class,
            'required' => false,
            'choice_label' => 'toString',
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }
}