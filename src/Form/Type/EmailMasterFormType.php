<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Form\Type;

use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Util\Form\Type\AutoTransFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Manuel Aguirre
 */
class EmailMasterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', TextType::class);
        $builder->add('description', TextareaType::class);
        $builder->add('layout', AutoTransFieldType::class, [
            'type' => TextareaType::class,
            'entry_constraints' => [new NotBlank()],
            'auto_save' => true,
        ]);
        $builder->add('target');
        $builder->add('editable');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailMaster::class,
        ]);
    }
}