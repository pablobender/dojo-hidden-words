<?php
require_once(__DIR__.'/TestHelper.php');
//$reservedWords = explode(',', '__halt_compiler(),abstract,and,array(),as,break,callable,case,catch,class,clone,const,continue,declare,default,die(),do,echo,else,elseif,empty(),enddeclare,endfor,endforeach,endif,endswitch,endwhile,eval(),exit(),extends,final,finally,for,foreach,function,global,goto,if,implements,include,include_once,instanceof,insteadof,interface,isset(),list(),namespace,new,or,print,private,protected,public,require,require_once,return,static,switch,throw,trait,try,unset(),use,var,while,xor,yield');

class GameTest extends PHPUnit_Framework_TestCase
{
  private $game;
  private $clock;

  public function setUp(){
    $this->game = new Game();
    $this->clock = new ClockFake();
    $this->game->setClock($this->clock);
    $this->game->setTimeout(300);
  }

  public function testDeveIniciarOJogo()
  {
    $this->game->setWords(array("w1"));
    $this->game->start();
    $this->assertTrue($this->game ->isRunning());
  }

  public function testNaoIniciaJogoVazio()
  {
    $this->game->setWords(array());
    $this->game->start();
    $this->assertFalse($this->game ->isRunning());
  }

  public function testSemPalavrasAbertas()
  {
    $this->game->setWords(array('if'));
    $this->game->start();
    $this->assertEquals(0, $this->game->getCountOpenedWords());
  }

  public function testComPalavrasAbertas()
  {
    $this->game->setWords(array('if'));
    $this->game->start();
    $this->game->openWord('if');
    $this->assertEquals(1, $this->game->getCountOpenedWords());
  }

  public function testComPalavrasDiferentes()
  {
    $this->game->setWords(array('if'));
    $this->game->start();
    $this->game->openWord('else');
    $this->assertEquals(0, $this->game->getCountOpenedWords());
  }

  public function testComPalavrasDuplicadas()
  {
    $this->game->setWords(array('if'));
    $this->game->start();
    $this->game->openWord('if');
    $this->game->openWord('if');
    $this->assertEquals(1, $this->game->getCountOpenedWords());
  }

  public function testAbrirPalavraComJogoNaoInciado(){
    $this->game->setWords(array('if'));
    try
    {
      $this->game->openWord('if');
      $this->fail('Exceção não lançada com jogo não iniciado');
    } catch(Exception $e) 
    {
      $this->assertEquals('Jogo não iniciado', $e->getMessage());
    }
  }

  public function testJogoCompletoQuandoAindaExistemPalavrasFechadas()
  {
    $this->game->setWords(array('if', 'else'));
    $this->game->start();
    $this->game->openWord('if');
    $this->assertFalse($this->game->isCompleted());
  }  

  public function testJogoCompleto()
  {
    $this->game->setWords(array('if', 'else'));
    $this->game->start();
    $this->game->openWord('if');
    $this->game->openWord('else');
    $this->assertTrue($this->game->isCompleted());
  }

  public function testJogoEmAndamentoQuantoAindaHaTempo()
  {
    $this->game->setWords(array('if', 'else'));
    $this->game->start();
    $this->clock->add(150);
    $this->assertFalse($this->game->isTimeout());
  }

  public function testJogoExcedidoQuantoTempoEsgotado()
  {
    $this->game->setWords(array('if', 'else'));
    $this->game->start();
    $this->clock->add(300);
    $this->assertTrue($this->game->isTimeout());
  }
}