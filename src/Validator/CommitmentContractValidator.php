<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Validator;

use App\Entity\CommitmentContract as CommitmentContractEntity;
use App\Repository\CommitmentContractRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CommitmentContractValidator extends ConstraintValidator
{
    private $commitmentContractRepository;

    public function __construct(CommitmentContractRepository $commitmentContractRepository)
    {
        $this->commitmentContractRepository = $commitmentContractRepository;
    }

    public function validate($commitmentContract, Constraint $constraint)
    {
        if (!$constraint instanceof CommitmentContract) {
            throw new UnexpectedTypeException($constraint, CommitmentContract::class);
        }

        $this->startAfterFinishValidate($commitmentContract);
        $this->notOpenContractsOnPeriodValidate($commitmentContract);
    }

    private function startAfterFinishValidate(CommitmentContractEntity $commitmentContract)
    {
        if (null === $commitmentContract->getFinishCycle()) {
            return;
        }

        if ($commitmentContract->getStartCycle()->getStart() > $commitmentContract->getFinishCycle()->getStart()) {
            $this->context->buildViolation('Le cycle de début doit être inférieur au cycle de fin')
                ->atPath('startCycle')
                ->addViolation();
        }
    }

    private function notOpenContractsOnPeriodValidate(CommitmentContractEntity $commitmentContract)
    {
        $openContracts = $this->commitmentContractRepository->findOpenForCommitmentContract($commitmentContract);
        if (0 === \count($openContracts)) {
            return;
        }

        $this->context->buildViolation('Il existe déjà au moins un contrat d\'ouvert sur la période')
                ->atPath('startCycle')
                ->addViolation();
    }
}
