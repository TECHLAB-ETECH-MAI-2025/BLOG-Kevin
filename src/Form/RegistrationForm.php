<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->	add('firstName', TextType::class, [
					'label' => 'Prénom',
					'required' => false,
					'attr' => [
						'class' => 'form-control mb-3',
						'placeholder' => 'Votre prénom'
					]
				])
				->add('lastName', TextType::class, [
					'label' => 'Nom',
					'required' => false,
					'attr' => [
						'class' => 'form-control mb-3',
						'placeholder' => 'Votre nom'
					]
				])
				->add('email', EmailType::class, [
					'label' => 'Email',
					'attr' => [
						'class' => 'form-control mb-3',
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
				->add('agreeTerms', CheckboxType::class, [
					'label' => 'J\'accepte les conditions d\'utilisation',
					'mapped' => false,
					'constraints' => [
						new IsTrue([
							'message' => 'Vous devez accepter nos conditions d\'utilisation.',
						]),
					],
					'attr' => [
						'class' => 'form-check-input mb-3'
					],
					'label_attr' => [
						'class' => 'form-check-label'
					]
				])
				->add('plainPassword', RepeatedType::class, [
					'type' => PasswordType::class,
					'mapped' => false,
					'first_options' => [
						'label' => 'Mot de passe',
						'attr' => [
							'class' => 'form-control mb-3',
							'autocomplete' => 'new-password',
							'placeholder' => 'Minimum 8 caractères'
						],
					],
					'second_options' => [
						'label' => 'Confirmer le mot de passe',
						'attr' => [
							'class' => 'form-control mb-3',
							'autocomplete' => 'new-password',
							'placeholder' => 'Répétez votre mot de passe'
						],
					],
					'invalid_message' => 'Les mots de passe ne correspondent pas.',
					'constraints' => [
						new NotBlank([
							'message' => 'Veuillez entrer un mot de passe',
						]),
						new Length([
							'min' => 8,
							'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
							'max' => 4096,
						]),
					],
				])
			;
		}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
