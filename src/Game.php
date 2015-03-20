<?php
class Game
{
  private $running = false;
  private $openedWords = array();
  private $words = array();
  private $clock;
  private $timeout;
  private $beginOfGame;

  public function setWords(array $words)
  {
    $this->words = $words;
  }

  public function setTimeout($seconds)
  {
    $this->timeout = $seconds;
  }

  public function setClock($clock)
  {
    $this->clock = $clock;
  }

  public function start() {
    if (count($this->words) == 0) return false;

    $this->beginOfGame = $this->clock->now();
    $this->running = true;
  }

  public function isRunning() {
    return $this->running;
  }

  public function getCountOpenedWords() {
    return count($this->openedWords);
  }

  public function openWord($word) {
    if (!$this->running)
    {
      throw new Exception('Jogo não iniciado');
    }
    if (in_array($word, $this->words) && !in_array($word, $this->openedWords)) {
      $this->openedWords[] = $word;
    }
  }

  public function isCompleted() {
    return $this->getCountOpenedWords() == count($this->words);
  }

  public function isTimeout()
  {
    return ($this->clock->now() - $this->beginOfGame) >= $this->timeout;
  }
}