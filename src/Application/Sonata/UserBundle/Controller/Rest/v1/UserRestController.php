<?php

namespace Application\Sonata\UserBundle\Controller\Rest\v1;

use Application\Sonata\UserBundle\Form\UserType;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Patch;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Application\Sonata\UserBundle\Entity\User;

class UserRestController extends FOSRestController
{
    /**
     * @return mixed
     */
    protected function getManager()
    {
        $repositoryName = "ApplicationSonataUserBundle:User";
        return $this->getDoctrine()->getRepository($repositoryName);
    }

    /**
     * Return an user identified by User Id.
     *
     * @ApiDoc(
     *   section="Users",
     *   output = "Application\Sonata\UserBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @Get("api/v1/get-user/{userId}")
     * @View()
     * @param int $userId User Id
     * @return array
     * @throws NotFoundHttpException when user does not exist
     */
    public function getUserAction($userId)
    {
        $entity = $this->getManager()->findOneBy(array('id' => $userId));

        if (!$entity) {
            throw $this->createNotFoundException("User not found.");
        }

        $view = \FOS\RestBundle\View\View::create($entity);
        $view->setData($entity)->setStatusCode(200);
        // $serializationContext = SerializationContext::create();
        // $serializationContext->enableMaxDepthChecks();
        // $view->setSerializationContext($serializationContext);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *     section="Users",
     *     description="Create a new user",
     *     input="Application\Sonata\UserBundle\Form\UserType",
     *     statusCodes = {
     *        200="Returned when successful",
     *        400="Returned when an error has occurred while user creation",
     *     }
     * )
     *
     * @Post("api/v1/register")
     * @View()
     * @param Request $request the request object
     * @return FormInterface
     */
    public function postUserAction(Request $request)
    {
        $username = $request->request->get("user")['username'];
        $email = $request->request->get("user")['email'];

        $user = $this->getManager()->findOneByUsername($username);

        if ($user) {
            // throw $this->createNotFoundException("Mobile Number already exists.");
            throw new HttpException(400, "Mobile Number already exists.");
        }

        $userEmail = $this->getManager()->findOneByEmail($email);

        if ($userEmail) {
            // throw $this->createNotFoundException("Email already exists.");
            throw new HttpException(400, "Email already exists.");
        }

        $form = $this->createForm(new UserType(), null, ['csrf_protection' => false]);
        $form->submit($request);

        if ($form->isValid()) {

            $user = $form->getData();

//            $otp = rand(1000, 9999);
//            $user->setOtp($otp);
            $user->setEnabled(true);

            $this->getManager()->add($user);

            $view = \FOS\RestBundle\View\View::create($user);

            $view->setData(array(
                "code" => 200,
                "message" => "User Created Successfully.",
                "fields" => array(
                    "user_id" => $view->getData()->getId(),
                )
            ))->setStatusCode(200);

//            $notify = $this->get('app.notify');
//            $notify->sendOtp($otp, $username);

            return $this->handleView($view);
        }
        return $form;
    }

    /**
     * @ApiDoc(
     *     section="Users",
     *     description="Update a existing user",
     *     input="Application\Sonata\UserBundle\Form\UserType",
     *     statusCodes = {
     *        200="Returned when successful",
     *        400="Returned when an error has occurred while user creation",
     *        404="Returned when unable to find a user",
     *     }
     * )
     * @Put("api/v1/update-user/{userId}")
     * @View()
     * @param int $userId User Id
     * @param Request $request the request object
     * @return FormInterface
     */
    public function putUserAction($userId, Request $request)
    {
        $user = $this->getManager()->find($userId);

        if (!$user) {
            throw $this->createNotFoundException("User does not exist.");
        }

        $form = $this->createForm(new UserType(), $user, ['csrf_protection' => false]);
        $form->submit($request, false);

        if ($form->isValid()) {

            $user = $form->getData();

            //Upload profile image to directory
            if(!empty($request->request->get("user")['profilePic'])) {
                $this->baseToImage($this->get('kernel')->getRootDir().'/../web/images/profile_'.$user->getId().'.jpg', $user->getProfilePic());
                $user->setProfilePic('profile_'.$user->getId().'.jpg');
            }

            // if(!empty($request->request->get("user")['license'])) {
            //     $this->baseToImage($this->get('kernel')->getRootDir().'/../web/images/license_'.$user->getId().'.jpg', $user->getLicense());
            //     $user->setLicense('license_'.$user->getId().'.jpg');
            // }

            $this->getManager()->add($user);
            $view = \FOS\RestBundle\View\View::create();

            $view->setData(array("code" => 200, "message" => "User updated successfully."))->setStatusCode(200);

            return $this->handleView($view);
        }

        return $form;
    }

//    /**
//     * Return an user identified by username and password.
//     *
//     * @ApiDoc(
//     *   section="Users",
//     *   output = "Application\Sonata\UserBundle\Entity\User",
//     *   statusCodes = {
//     *     200 = "Returned when successful",
//     *     404 = "Returned when the user is not found"
//     *   }
//     * )
//     *
//     * @Get("api/v1/login/{username}/{password}")
//     * @View()
//     * @param string $username Username
//     * @param string $password Password
//     * @return array
//     * @throws NotFoundHttpException when user does not exist
//     */
//    public function getLoginAction($username, $password)
//    {
//        $entity = $this->getManager()->findOneBy(array('username' => $username));
//
//        if (!$entity) {
//            throw $this->createNotFoundException("User not found.");
//        }
//
//        $view = \FOS\RestBundle\View\View::create($entity);
//
//        $factory = $this->get('security.encoder_factory');
//        $encoder = $factory->getEncoder($entity);
//
//        if ($encoder->isPasswordValid($entity->getPassword(), $password, $entity->getSalt())) {
//
//            $view->setData(array(
//                    "code" => 200,
//                    "message" => "User login successful.",
//                    "fields" => array(
//                        "user_id" => $view->getData()->getId(),
//                        "otp_verified" => $view->getData()->getOtpVerified(),
//                        "enabled" => $view->getData()->isEnabled(),
//                    ))
//            )->setStatusCode(200);
//        } else {
//            //$view->setData(array("code" => 400, "message" => "Invalid username or password."))->setStatusCode(400);
//            throw $this->createNotFoundException("Invalid username or password.");
//        }
//
//        return $this->handleView($view);
//    }

    /**
     * Return an user identified by username and password.
     *
     * @ApiDoc(
     *   section="Users",
     *   output = "Application\Sonata\UserBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Invalid username and password",
     *     403 = "Invalid token",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @RequestParam(name="username", allowBlank=false, strict=true, nullable=false, description="Username of the user")
     * @RequestParam(name="password", allowBlank=false, strict=true, nullable=false, description="Password of the user")
     *
     * @Post("api/v1/login")
     * @View()
     * @param Request $request the request object
     * @return array
     * @throws NotFoundHttpException when user does not exist
     */
    public function postLoginAction(Request $request)
    {
        $apiKey = $request->headers->get('apiKey');

        if (!$apiKey || ($apiKey !=  "Fe9wFRl6Pni4nWL2XAgw")) {
            throw new HttpException(403, "Invalid Token");
        }

        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $entity = $this->getManager()->findOneBy(array('username' => $username));

        if (!$entity) {
            throw $this->createNotFoundException("User not found.");
        }

        $view = \FOS\RestBundle\View\View::create($entity);

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($entity);

        if ($encoder->isPasswordValid($entity->getPassword(), $password, $entity->getSalt())) {

            $view->setData(array(
                    "code" => 200,
                    "message" => "User login successful.",
                    "fields" => array(
                        "user_id" => $view->getData()->getId(),
                        // "otp_verified" => $view->getData()->getOtpVerified(),
                        "enabled" => $view->getData()->isEnabled(),
                    ))
            )->setStatusCode(200);
        } else {
            throw new HttpException(401, "Invalid username or password");
        }

        return $this->handleView($view);
    }

    // /**
    //  * Validate an user identified by User Id and Otp.
    //  *
    //  * @ApiDoc(
    //  *   section="Users",
    //  *   output = "Application\Sonata\UserBundle\Entity\User",
    //  *   statusCodes = {
    //  *     200 = "Returned when successful",
    //  *     404 = "Returned when the user is not found"
    //  *   }
    //  * )
    //  *
    //  * @Get("api/v1/validate-otp/{userId}/{otp}")
    //  * @View()
    //  * @param string $userId User Id
    //  * @param string $otp Otp
    //  * @param Request $request the request object
    //  * @return array
    //  * @throws NotFoundHttpException when Otp does not exist
    //  */
    // public function getValidateOtpAction($userId, $otp, Request $request)
    // {
    //     $entity = $this->getManager()->findOneBy(array('id' => $userId, 'otp' => $otp));

    //     if (!$entity) {
    //         throw $this->createNotFoundException("User not found.");
    //     }

    //     $form = $this->createForm(new UserType(), $entity, ['csrf_protection' => false]);
    //     $form->submit($request, false);

    //     if ($form->isValid()) {

    //         $user = $form->getData();
    //         $user->setOtp(0);
    //         $user->setOtpVerified(true);
    //         $this->getManager()->add($user);

    //         $view = \FOS\RestBundle\View\View::create($user);
    //         $view->setData(array(
    //                 "code" => 200,
    //                 "message" => "Otp verified successfully.",
    //                 "fields" => array(
    //                     "user_id" => $view->getData()->getId()
    //                 ))
    //         )->setStatusCode(200);

    //         return $this->handleView($view);
    //     }

    //     return $form;
    // }

    /**
     * Reset user password identified by user id and otp.
     *
     * @ApiDoc(
     *   section="Users",
     *   output = "Application\Sonata\UserBundle\Entity\User",
     *   input="Application\Sonata\UserBundle\Form\UserType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @Put("api/v1/reset-password/{userId}/{otp}")
     * @View()
     * @param string $userId User Id
     * @param string $otp Otp
     * @param Request $request the request object
     * @return array
     * @throws NotFoundHttpException when Otp does not exist
     */
    public function putResetPasswordAction($userId, $otp, Request $request)
    {
        $entity = $this->getManager()->findOneBy(array('id' => $userId, 'otp' => $otp));

        if (!$entity) {
            throw $this->createNotFoundException("User not found.");
        }

        $form = $this->createForm(new UserType(), $entity, ['csrf_protection' => false]);
        $form->submit($request, false);

        if ($form->isValid()) {

            $user = $form->getData();
            $user->setOtp(0);
            $user->setOtpVerified(true);
            $this->getManager()->add($user);

            $view = \FOS\RestBundle\View\View::create($user);
            $view->setData(array(
                    "code" => 200,
                    "message" => "Password reset successfully.",
                    "fields" => array(
                        "user_id" => $view->getData()->getId()
                    ))
            )->setStatusCode(200);

            return $this->handleView($view);
        }

        return $form;
    }
//
//    /**
//     * Sends Otp by username.
//     *
//     * @ApiDoc(
//     *   section="Users",
//     *   output = "Application\Sonata\UserBundle\Entity\User",
//     *   statusCodes = {
//     *     200 = "Returned when successful",
//     *     404 = "Returned when the user is not found"
//     *   }
//     * )
//     *
//     * @Get("api/v1/send-otp/{username}")
//     * @View()
//     * @param string $username User username
//     * @param Request $request the request object
//     * @return array
//     * @throws NotFoundHttpException when username does not exist
//     */
//    public function getSendOtpAction($username, Request $request)
//    {
//        $entity = $this->getManager()->findOneBy(array('username' => $username));
//
//        if (!$entity) {
//            throw $this->createNotFoundException("User not found.");
//        }
//
//        $form = $this->createForm(new UserType(), $entity, ['csrf_protection' => false]);
//        $form->submit($request, false);
//
//        if ($form->isValid()) {
//
//            $user = $form->getData();
//            $otp = rand(1000, 9999);
//            $user->setOtp($otp);
//            $this->getManager()->add($user);
//
//            $view = \FOS\RestBundle\View\View::create($user);
//            $view->setData(array(
//                    "code" => 200,
//                    "message" => "Otp sent successfully.",
//                    "fields" => array(
//                        "user_id" => $view->getData()->getId()
//                    ))
//            )->setStatusCode(200);
//
//            $notify = $this->get('app.notify');
//            $notify->sendOtp($otp, $username);
//
//            return $this->handleView($view);
//        }
//
//        return $form;
//    }

    /**
     * Reset user password identified by user id and old password.
     *
     * @ApiDoc(
     *   section="Users",
     *   output = "Application\Sonata\UserBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     204 = "No Content",
     *     400 = "Validation Failed",
     *     403 = "Invalid Token",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @RequestParam(name="userId", allowBlank=false, strict=true, nullable=false, description="Id of the user")
     * @RequestParam(name="oldPassword", allowBlank=false, strict=true, nullable=false, description="Old password of the user")
     * @RequestParam(name="newPassword", allowBlank=false, strict=true, nullable=false, description="New password of the user")
     *
     * @Put("api/v1/reset-password")
     * @View()
     * @param Request $request the request object
     * @return array
     * @throws NotFoundHttpException when User does not exist
     * @throws HttpException when Password is invalid
     */
    public function putPasswordAction(Request $request)
    {
        $apiKey = $request->headers->get('apiKey');

        if (!$apiKey || ($apiKey !=  "b0j86P80C976Aa8z14D7x")) {
            throw new HttpException(403, "Invalid Token");
        }

        $userId = $request->request->get('userId');
        $oldPassword = $request->request->get('oldPassword');
        $newPassword = $request->request->get('newPassword');

        $entity = $this->getManager()->findOneBy(array('id' => $userId));

        if (!$entity) {
            throw $this->createNotFoundException("User not found.");
        }

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($entity);

        if (!$encoder->isPasswordValid($entity->getPassword(), $oldPassword, $entity->getSalt())) {
            throw new HttpException(400, "Invalid Password");
        }

        $form = $this->createForm(new UserType(), $entity, ['csrf_protection' => false]);
        $form->submit($request, false);

        if ($form->isValid()) {

            $user = $form->getData();
            $newPassword = $encoder->encodePassword($newPassword, $entity->getSalt());
            $user->setPassword($newPassword);

            $this->getManager()->add($user);

            $view = \FOS\RestBundle\View\View::create($user);
            $view->setData(array(
                    "code" => 200,
                    "message" => "Password reset successfully.",
                )
            )->setStatusCode(200);

            return $this->handleView($view);
        }

        return $form;
    }

    protected function baseToImage($filePath, $base64_string)
    {
        $ifp = fopen($filePath, "wb");
        $data = explode(',', $base64_string);
        $imgdata = (isset($data[1]) ? $data[1] : $base64_string);
        fwrite($ifp, base64_decode($imgdata));
        fclose($ifp);
        return $filePath;
    }
}
