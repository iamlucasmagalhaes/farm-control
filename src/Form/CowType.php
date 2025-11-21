<?php

    namespace App\Form;

    use App\Entity\Farm;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\FormBuilderInterface;

    class CowType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('code', TextType::class, [
                    'label' => 'Código do gado:',
                ])

                ->add('milkperweek', NumberType::class, [
                    'label' => 'Leite produzido por semana (Litros):',
                    'html5' => true,
                    'scale' => 2,
                    'attr' => ['step' => '0.01'],
                ])

                ->add('foodperweek', NumberType::class, [
                    'label' => 'Alimento ingerido por semana (Kg):',
                    'html5' => true,
                    'scale' => 2,
                    'attr' => ['step' => '0.01'],
                ])

                ->add('weight', NumberType::class, [
                    'label' => 'Peso do animal (Kg):',
                    'html5' => true,
                    'scale' => 2,
                    'attr' => ['step' => '0.01'],
                ])

                ->add('birthdate', DateType::class, [
                    'label' => 'Data de nascimento:',
                    'widget' => 'single_text',
                    'html5' => true,
                ])

                ->add('farm', EntityType::class, [
                    'class' => Farm::class,
                    'choice_label' => 'name',
                    'label' => 'Fazenda:',
                    'placeholder' => 'Selecione uma fazenda',
                ])

                ->add('isslaughtered', CheckboxType::class, [
                    'label' => 'Foi abatido?',
                    'required' => false,
                ])

                ->add('slaughterdate', DateType::class, [
                    'label' => 'Data do abate:',
                    'widget' => 'single_text',
                    'html5' => true,
                    'required' => false,
                ])
                ->add('Salvar', SubmitType::class);
        }
    }
