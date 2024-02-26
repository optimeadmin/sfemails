<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Form\Type;

use Optime\Email\Bundle\Constraints\TwigContent;
use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use Optime\Util\Form\Type\AutoTransFieldType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Manuel Aguirre
 */
class EmailTemplateFormType extends AbstractType
{
    public function __construct(private EmailAppProvider $appProvider)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('app', EntityType::class, [
            'class' => $this->appProvider->getEmailAppClass(),
        ]);
        $builder->add('config');
        $builder->add('useCustomLayout', CheckboxType::class, [
            'getter' => function (?EmailTemplate $template) {
                return (bool)$template?->getCustomLayout();
            },
            'setter' => fn() => null,
            'label' => 'Use Custom Layout'
        ]);
        $builder->add('customLayout', EntityType::class, [
            'required' => false,
            'label' => 'Select Custom Layout',
            'class' => EmailLayout::class,
            'choice_label' => 'label',
        ]);
        $builder->add('subject', AutoTransFieldType::class, [
            'entry_constraints' => [new NotBlank()],
            'auto_save' => true,
        ]);
        $builder->add('content', AutoTransFieldType::class, [
            'type' => TextareaType::class,
            'entry_constraints' => [new NotBlank(), new TwigContent()],
            'auto_save' => true,
            'item_options' => [
                'attr' => [
                    'rows' => 5,
                    'data-code-mirror' => true,
                ],
                'required' => false,
            ],
        ]);
        $builder->add('active');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailTemplate::class,
            'auto_save_translations' => true,
        ]);
    }
}