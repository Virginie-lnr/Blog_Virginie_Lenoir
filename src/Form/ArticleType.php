<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('contenu')
            ->add('imageFile', FileType::class, [
                'label' => 'Image (optionnel)', 
                'required'    => false
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class, 
                // 'query_builder' => function (EntityRepository $er){
                //     return $er->createQueryBuilder('a')
                //             ->orderBy('a.label', 'ASC');
                // }, 
                'label' => 'Catégories (vous pouvez en choisir plusieurs)', 
                'multiple'   => true, 
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
