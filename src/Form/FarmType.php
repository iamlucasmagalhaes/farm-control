<?php

    namespace App\Form;

    use App\Entity\Veterinarian;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\FormBuilderInterface;

    class FarmType extends AbstractType{

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('name', TextType::class, ['label' => 'Nome da fazenda: '])
                ->add('size', NumberType::class, [
                    'label' => 'Tamanho da fazenda em Hectares(HA): ',
                    'scale' => 2,  //permite números decimais com 2 casas
                    'html5' => true  //ativa o <input type="number">
                ])
                ->add('responsible', TextType::class, ['label' => 'Nome do responsável'])
                ->add('veterinarian', EntityType::class, [
                    'class' => Veterinarian::class,
                    'choice_label' => 'name', //campo que vai aparecer na lista
                    'multiple' => true, //permite selecionar mais de um
                    'expanded' => true, //mostra como checkbox
                    'label' => 'Veterinários: ',
                ])
                ->add('Salvar', SubmitType::class);
            return parent::buildForm($builder, $options);
        }
    }