<?php

namespace BestTrip\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;

class GrapheController extends Controller {

    public function chartLineAction() {
        $em = $this->getDoctrine()->getManager();
        $guides = $em->getRepository('GuideBundle:guide')->guideData();
        $series = array(
            array('name' => "Guide", "data" => $guides[1])
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart'); // #id du div où afficher le graphe
        $ob->title->text('Les guides les plus récommandés');


        $ob->xAxis->title(array('text' => "Les guides"));
        $ob->yAxis->title(array('text' => "Nombre de récommendations "));
        $ob->series($series);
        return $this->render('AdminBundle:Graphe:LineChart.html.twig', array(
            'chart' => $ob
        ));
    }

    public function chartHistogrammeAction() {
        $em = $this->getDoctrine()->getManager();
        $guides = $em->getRepository('GuideBundle:Guide')->retrieveData();

        $series = array(
            array(
                'name' => 'Nombre des évaluations',
                'type' => 'column',
                'color' => '#4572A7',
                'yAxis' => 1,
                'data' => $guides[1],
            ),
        );
        $yData = array(
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + " x" }'),
                    'style' => array('color' => '#AA4643')
                ),
                'opposite' => true,
            ),
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + " ev" }'),
                    'style' => array('color' => '#4572A7')
                ),
                'gridLineWidth' => 0,
                'title' => array(
                    'text' => 'Nombre des évaluations',
                    'style' => array('color' => '#4572A7')
                ),
            ),
        );
        $categories = $guides[0];
        $ob = new Highchart();
        $ob->chart->renderTo('container'); // The #id of the div where to render the chart
        $ob->chart->type('column');
        $ob->title->text('Les Guides les plus évalués');
        $ob->xAxis->categories($categories);
        $ob->yAxis($yData);
        $ob->legend->enabled(false);
        $formatter = new Expr('function () {
var unit = {
"Nombre des évaluations": "ev",

}[this.series.name];
return this.x + ": <b>" + this.y + "</b> " + unit;
}');
        $ob->tooltip->formatter($formatter);
        $ob->series($series);
        return $this->render('AdminBundle:Graphe:histogramme.html.twig', array(
                    'chart' => $ob
        ));
    }

    public function chartPieAction() {
        $em = $this->getDoctrine()->getManager();
        $guides = $em->getRepository('GuideBundle:Guide')->ExpData();
        $ob = new Highchart();
        $ob->chart->renderTo('piechart');
        $ob->title->text('Guides par pays');
        $ob->plotOptions->pie(array(
            'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array('enabled' => false),
            'showInLegend' => true
        ));
        $data = array(
            array('Guide1', 45.0),
            array('Guide2', 26.8),
            array('Guide3', 12.8),
            array('Guide4', 8.5),
            array('Guide5', 6.2),
            array('Guide6', 0.7),
        );

        $ob->series(array(array('type' => 'pie', 'name' => 'Browser share', 'data' => $guides)));
        return $this->render('AdminBundle:Graphe:pie.html.twig', array(
                    'chart' => $ob
        ));
    }

}
