<?php

namespace App\Test\Controller;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TeacherControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/teacher/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Teacher::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Teacher index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'teacher[username]' => 'Testing',
            'teacher[roles]' => 'Testing',
            'teacher[password]' => 'Testing',
            'teacher[first_name]' => 'Testing',
            'teacher[last_name]' => 'Testing',
            'teacher[phone_number]' => 'Testing',
            'teacher[imageName]' => 'Testing',
            'teacher[imageSize]' => 'Testing',
            'teacher[gender]' => 'Testing',
            'teacher[email]' => 'Testing',
            'teacher[students]' => 'Testing',
            'teacher[branches]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Teacher();
        $fixture->setUsername('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setFirst_name('My Title');
        $fixture->setLast_name('My Title');
        $fixture->setPhone_number('My Title');
        $fixture->setImageName('My Title');
        $fixture->setImageSize('My Title');
        $fixture->setGender('My Title');
        $fixture->setEmail('My Title');
        $fixture->setStudents('My Title');
        $fixture->setBranches('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Teacher');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Teacher();
        $fixture->setUsername('Value');
        $fixture->setRoles('Value');
        $fixture->setPassword('Value');
        $fixture->setFirst_name('Value');
        $fixture->setLast_name('Value');
        $fixture->setPhone_number('Value');
        $fixture->setImageName('Value');
        $fixture->setImageSize('Value');
        $fixture->setGender('Value');
        $fixture->setEmail('Value');
        $fixture->setStudents('Value');
        $fixture->setBranches('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'teacher[username]' => 'Something New',
            'teacher[roles]' => 'Something New',
            'teacher[password]' => 'Something New',
            'teacher[first_name]' => 'Something New',
            'teacher[last_name]' => 'Something New',
            'teacher[phone_number]' => 'Something New',
            'teacher[imageName]' => 'Something New',
            'teacher[imageSize]' => 'Something New',
            'teacher[gender]' => 'Something New',
            'teacher[email]' => 'Something New',
            'teacher[students]' => 'Something New',
            'teacher[branches]' => 'Something New',
        ]);

        self::assertResponseRedirects('/teacher/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getUsername());
        self::assertSame('Something New', $fixture[0]->getRoles());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getFirst_name());
        self::assertSame('Something New', $fixture[0]->getLast_name());
        self::assertSame('Something New', $fixture[0]->getPhone_number());
        self::assertSame('Something New', $fixture[0]->getImageName());
        self::assertSame('Something New', $fixture[0]->getImageSize());
        self::assertSame('Something New', $fixture[0]->getGender());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getStudents());
        self::assertSame('Something New', $fixture[0]->getBranches());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Teacher();
        $fixture->setUsername('Value');
        $fixture->setRoles('Value');
        $fixture->setPassword('Value');
        $fixture->setFirst_name('Value');
        $fixture->setLast_name('Value');
        $fixture->setPhone_number('Value');
        $fixture->setImageName('Value');
        $fixture->setImageSize('Value');
        $fixture->setGender('Value');
        $fixture->setEmail('Value');
        $fixture->setStudents('Value');
        $fixture->setBranches('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/teacher/');
        self::assertSame(0, $this->repository->count([]));
    }
}
