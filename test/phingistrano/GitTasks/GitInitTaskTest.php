<?php
/*
 *  $Id: GitInitTaskTest.php 1179 2011-06-22 11:31:08Z mp $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://phing.info>.
 */
 
require_once 'phing/BuildFileTest.php';
require_once '../classes/phing/tasks/ext/git/GitInitTask.php';
require_once dirname(__FILE__) . '/GitTestsHelper.php';

/**
 * @author Victor Farazdagi <simple.square@gmail.com>
 * @version $Id: GitInitTaskTest.php 1179 2011-06-22 11:31:08Z mp $
 * @package phing.tasks.ext
 */
class GitInitTaskTest extends BuildFileTest { 

    public function setUp() { 
        // set temp directory used by test cases
        mkdir(PHING_TEST_TMP . '/git');

        $this->configureProject(__DIR__ . '/GitInitTaskTest.xml');
    }

    public function tearDown()
    {
        GitTestsHelper::rmdir(PHING_TEST_TMP . '/git');
    }

    public function testWrongRepository()
    {
        $this->expectBuildExceptionContaining('wrongRepository', 
            'Repository directory not readable', 
            'You must specify readable directory as repository.');
    }

    public function testGitInit()
    {
        $repository = PHING_TEST_TMP . '/git';
        $gitFilesDir = $repository . '/.git';
        $this->executeTarget('gitInit');

        $this->assertInLogs('git-init: initializing "' . $repository . '" repository');
        $this->assertTrue(is_dir($repository));
        $this->assertTrue(is_dir($gitFilesDir));
    }

    public function testGitInitBare()
    {
        $repository = PHING_TEST_TMP . '/git';
        $gitFilesDir = $repository . '/.git';
        $this->executeTarget('gitInitBare');
        $this->assertInLogs('git-init: initializing (bare) "' . $repository . '" repository');
        $this->assertTrue(is_dir($repository));
        $this->assertTrue(is_dir($repository . '/branches'));
        $this->assertTrue(is_dir($repository . '/info'));
        $this->assertTrue(is_dir($repository . '/hooks'));
        $this->assertTrue(is_dir($repository . '/refs'));
    }

    public function testNoRepositorySpecified()
    {
        $this->expectBuildExceptionContaining('noRepository', 
            'Repo dir is required',
            '"repository" is required parameter');
    }

}
