<?php

namespace Automation\Server;

use Coyl\Git\Git;
use Coyl\Git\GitRepo;

class GitHelper
{
    /**
     * @var GitRepo
     */
    private $git;
    /**
     * @var string
     */
    private $defaultBranch;

    /**
     * GitHelper constructor.
     *
     * @param GitRepo $git
     * @param string  $defaultBranch
     */
    public function __construct(GitRepo $git, $defaultBranch = 'master')
    {
        $this->git = $git;
        $this->defaultBranch = $defaultBranch;
    }

    /**
     * @param string      $lastRevision
     * @param null|string $firstRevision
     *
     * @return string
     */
    public function getDiffForAllFiles($lastRevision, $firstRevision = null, $filesOnly = false)
    {
        if (in_array($firstRevision, [null, Git::ZERO_REVISION], true)) {
            $firstRevision = $this->defaultBranch;
        }
        $log = $this->git->log(sprintf("--no-merges %s..%s --pretty=format:'%%H'", $firstRevision, $lastRevision));
        $log = explode("\n", $log);
        $diff = '';
        if ($filesOnly) {
            $format = '--pretty=format: --name-status %s';
        } else {
            $format = '--pretty=format: %s';
        }
        foreach ($log as $hash) {
            $diff .=  $this->git->show(sprintf($format, $hash)) . "\n";
        }

        return $diff;
    }

    public function getChangedFiles($lastRevision, $firstRevision = null, $extensions = [], $excludeDeleted = true)
    {
        $files = $this->getDiffForAllFiles($lastRevision, $firstRevision, true);
        if ($excludeDeleted) {
            $files = array_filter($files, function ($el) {
                return strpos($el, "D\t") !== 0;
            });
        }
        $files = array_map(function ($el) {
            $bits = explode("\t", $el);
            isset($bits[1]) ? $bits[1] : $bits[0];
        }, $files);
        return $files;
    }

    public function getFileInRevision($file, $revision)
    {
        return $this->git->show(sprintf('%s:%s', $file, $revision));
    }

//    public function getDiffForNewBranch($new_revision, $diff_type = Model_Gitosis::DIFF_TYPE_CUMULATIVE)
//    {
//        if ($diff_type == Model_Gitosis::DIFF_TYPE_CUMULATIVE) {
//            $this->Script->exec("git log --no-merges " . $this->_default_branch . ".." . $new_revision, $log);
//            $this->Script->exec(
//                "git log --no-merges " . $this->_default_branch . ".." . $new_revision . " --pretty=format:'%H' | xargs -I {} git show --pretty=format: {} | cat",
//                $diff
//            );
//        } else {
//            $this->Script->exec("git log --oneline --no-merges " . $this->_default_branch . ".." . $new_revision, $log);
//            $diff = array();
//        }
//        $this->Script->exec("git log --pretty=format: --name-status --no-merges " . $this->_default_branch . ".." . $new_revision, $files);
//
//        $log = implode("\n", $log);
//        $diff = implode("\n", $diff);
//        $files = implode("\n", array_unique($files));
//
//        return array('log' => $log, 'diff' => $diff, 'files' => $files, 'diff_no_merge' => $diff);
//    }
//
//    public function getDiff($first_commit, $new_revision, $diff_type = Model_Gitosis::DIFF_TYPE_CUMULATIVE)
//    {
//        if ($diff_type == Model_Gitosis::DIFF_TYPE_CUMULATIVE) {
//            $this->Script->exec('git log ' . $first_commit . '..' . $new_revision, $log);
//            $this->Script->exec('git diff -w -M -C ' . $first_commit . '..' . $new_revision, $diff);
//            $log = implode("\n", $log);
//            $diff = implode("\n", $diff);
//            $this->Script->exec('git diff --name-status ' . $first_commit . '..' . $new_revision, $output);
//            $files = implode("\n", $output);
//        } else {
//            $this->Script->exec("git log --oneline --pretty=format:%H " . $first_commit . ".." . $new_revision, $log);
//            $diff = array();
//            foreach ($log as $commit) {
//                $d = array();
//                $s = array();
//                $f = array();
//                $this->Script->exec("git show -m " . $commit, $d);
//                $this->Script->exec('git log -1 --pretty="format:%aN (%aE) %t %s" ' . $commit, $s);
//                $this->Script->exec('git show -m --name-status --pretty=format: ' . $commit, $f);
//                $diff[] = array(
//                    'diff' => implode("\n", $d),
//                    'subj' => implode("\n", $s),
//                    'files' => implode("\n", array_unique($f)),
//                );
//            }
//            $log = '';
//            $files = '';
//        }
//        return array('log' => $log, 'diff' => $diff, 'files' => $files, 'diff_no_merge' => $diff);
//    }
//
//    public function getDiffForOldBranch($old_revision, $new_revision, $diff_type = Model_Gitosis::DIFF_TYPE_CUMULATIVE)
//    {
//        $diff_no_merge = '';
//        if ($diff_type == Model_Gitosis::DIFF_TYPE_CUMULATIVE) {
//            $refspec = $new_revision . " ^" . $old_revision . " ^" . $this->_default_branch;
//            $this->Script->exec("git log $refspec | cat", $log);
//            $this->Script->exec(
//                "git log $refspec --oneline | awk '{print \$1}' | xargs --no-run-if-empty git show --pretty=format: | cat",
//                $diff
//            );
//            $this->Script->exec(
//                "git log $refspec --no-merges --oneline | awk '{print \$1}' | xargs --no-run-if-empty git show --pretty=format: | cat",
//                $diff_no_merge
//            );
//            $this->Script->exec(
//                "git log $refspec --oneline | awk '{print \$1}' | xargs --no-run-if-empty git show --pretty=format: --name-status | cat",
//                $files
//            );
//
//            $log = implode("\n", $log);
//            $diff = implode("\n", $diff);
//            $diff_no_merge = implode("\n", $diff_no_merge);
//            $files = implode("\n", array_unique($files));
//        } else {
//            $this->Script->exec("git log --oneline --pretty=format:%H " . $old_revision . ".." . $new_revision, $log);
//            $diff = array();
//            foreach ($log as $commit) {
//                $d = array();
//                $s = array();
//                $f = array();
//                $this->Script->exec("git show -m " . $commit, $d);
//                $this->Script->exec('git log -1 --pretty="format:%t %s" ' . $commit, $s);
//                $this->Script->exec('git show -m --name-status --pretty=format: ' . $commit, $f);
//                $diff[] = array(
//                    'diff' => implode("\n", $d),
//                    'subj' => implode("\n", $s),
//                    'files' => implode("\n", array_unique($f)),
//                );
//            }
//            $log = '';
//            $files = '';
//        }
//        return array('log' => $log, 'diff' => $diff, 'diff_no_merge' => $diff_no_merge, 'files' => $files);
//    }

}
