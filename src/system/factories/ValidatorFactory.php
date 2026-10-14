<?php
namespace Wiki\system\factories;

use Wiki\system\validators\CheckBoxValidator;
use Wiki\system\validators\EmailValidator;
use Wiki\system\validators\ImgValidator;
use Wiki\system\validators\NewPasswordValidator;
use Wiki\system\validators\SortValidator;
use Wiki\system\validators\TextValidator;
use Wiki\tools\interfaces\iValidator;

enum ValidatorFactory: string
{
    case VTEXT = 'text';
    case VEMAIL = 'email';
    case VPASSWORD = 'password';
    case VNEWPASS = 'new_password';
    case VTEXTAREA = 'textarea';
    case VFILE = 'file';
    case VSORT = 'select';
    case VCHECKBOX = 'checkboxgroup';

    case VSCHECKBOX = 'SearchableCheckboxes';
    public function createValidator(): iValidator
    {
        return match ($this) {
            self::VTEXT => new TextValidator(),
            self::VEMAIL => new EmailValidator(),
            self::VPASSWORD => new TextValidator(),
            self::VTEXTAREA => new TextValidator(),
            self::VNEWPASS => new NewPasswordValidator(),
            self::VFILE => new ImgValidator(),
            self::VSORT => new SortValidator(),
            self::VCHECKBOX => new CheckBoxValidator(),
            self::VSCHECKBOX => new CheckBoxValidator()
        };
    }
}