<?php

namespace App\Controller\pdf_calandar;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CalandarDayRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfGeneratorController extends AbstractController
{
    #[Route('/getpdf', name: 'app_pdf_generator')]
    public function index(CalandarDayRepository $calRepo ,EntityManagerInterface $em ): Response
    {

        $doctorId=null;
        if($this->getUser() && $this->getUser()->getRoles()[0]=="ROLE_DOCTOR"){
            $user = $em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            $doctorId=$user->getId();
        }else{
            return $this->redirectToRoute('error_404', [
                'errormsg' => "404-maybe-you-have-no-right-for-now"
            ]); 
        }

        $calandrs=$calRepo->findBy(
            ['doctor'=>   $doctorId ],
            ['date'=>  'ASC' ]
        );


        // Load Dompdf library with options
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        // Render template to HTML
        $html = $this->renderView('pdf_generator/calanadars_view.html.twig', [
            'calendarDays'=>$calandrs
        ]);

        // Load HTML into Dompdf
        $dompdf->loadHtml($html);

        // Set page size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Output PDF to browser
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="document.pdf"',
            ]
        );
    
    }
}
