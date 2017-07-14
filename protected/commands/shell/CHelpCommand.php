<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CleanUp
 *
 * @author kevinwang
 */
class CHelpCommand extends CConsoleCommand {

    /**
     * Execute the action.
     * @param array $args command line parameters specific for this command
     */
    public function run($args) {
        $runner = $this->getCommandRunner();
        $commands = $runner->commands;
        if (isset($args[0]))
            $name = strtolower($args[0]);
        if (!isset($args[0]) || !isset($commands[$name])) {
            if (!emptyempty($commands)) {
                echo "Yii command runner (based on Yii v" . Yii::getVersion() . ")\n";
                echo "Usage: " . $runner->getScriptName() . " <command-name> [parameters...]\n";
                echo "\nThe following commands are available:\n";
                $commandNames = array_keys($commands);
                sort($commandNames);
                echo ' - ' . implode("\n - ", $commandNames);
                echo "\n\nTo see individual command help, use the following:\n";
                echo "   " . $runner->getScriptName() . " help <command-name>\n";
            } else {
                echo "No available commands.\n";
                echo "Please define them under the following directory:\n";
                echo "\t" . Yii::app()->getCommandPath() . "\n";
            }
        } else
            echo $runner->createCommand($name)->getHelp();
    }

    /**
     * Provides the command description.
     * @return string the command description.
     */
    public function getHelp() {
        return parent::getHelp() . ' [command-name]';
    }

}
