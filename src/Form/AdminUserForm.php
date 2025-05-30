<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->	add('firstName', TextType::class, [
				'label' => 'Prénom',
				'required' => false,
				'attr' => [
					'class' => 'form-control',
					'placeholder' => 'Prénom'
				]
			])
			->add('lastName', TextType::class, [
				'label' => 'Nom',
				'required' => false,
				'attr' => [
					'class' => 'form-control',
					'placeholder' => 'Nom'
				]
			])
			->add('email', EmailType::class, [
				'label' => 'Email',
				'attr' => [
					'class' => 'form-control',
					'placeholder' => 'exemple@domaine.com'
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Veuillez entrer une adresse email',
					]),
					new Email([
						'message' => 'L\'adresse email n\'est pas valide',
					])
				]
			])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Super Administrateur' => 'ROLE_SUPER_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'label_attr' => ['class' => 'form-label'],

            ])
			->add('isVerified', CheckboxType::class, [
				'label' => 'Email vérifié',
				'required' => false,
				'attr' => [
					'class' => 'form-check-input'
				],
				'label_attr' => [
					'class' => 'form-check-label'
				]
			])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'required' => $options['is_new_user'],
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'new-password',
					'placeholder' => $options['is_new_user'] ? 'Mot de passe' : 'Laisser vide pour ne pas modifier'
                ],

                'constraints' => $options['is_new_user'] ? [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ] : [],
            ])

		;
	}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new_user' => false,
        ]);
    }
}
