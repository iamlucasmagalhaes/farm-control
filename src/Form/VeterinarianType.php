<?php

    namespace App\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;

    class VeterinarianType extends AbstractType{
        
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('crmv', TextType::class, ['label' => 'CRMV do Profissional: '])
                ->add('name', TextType::class, ['label' => 'Nome do Profissional: '])
                ->add('Salvar', SubmitType::class);
            return parent::buildForm($builder, $options);
        }
    }