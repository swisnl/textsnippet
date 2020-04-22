<?php

declare(strict_types=1);

namespace Tests;


use PHPUnit\Framework\TestCase;
use Swis\TextSnippet;

/**
 * @backupStaticAttributes disabled
 */
class InspectorTest extends TestCase
{
	protected $specialChars = ['Â', 'Ã', 'Ä', 'À', 'Á', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];

	protected $lorumIpsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vel ex enim. Aliquam ullamcorper volutpat tellus sed venenatis. Nulla mattis blandit odio, non porttitor orci dapibus vel. Cras fringilla luctus quam at varius. Donec quis urna iaculis, pulvinar libero vitae, auctor purus. Nunc turpis ante, vehicula eu nisl at, tristique fringilla est. In fringilla varius mi, sit amet bibendum dui gravida vitae. Etiam bibendum lorem nec tempus sollicitudin.

Etiam mattis eu quam in tempus. Ut dignissim odio sem, ut scelerisque felis malesuada vitae. Sed in dapibus lorem. Cras ullamcorper lobortis augue in auctor. Donec faucibus maximus mi sed congue. Vivamus et vehicula eros, scelerisque venenatis mi. Duis posuere nulla eros, at congue est ornare a. Aliquam erat volutpat.

Praesent nec nisi et neque ornare eleifend sed et lacus. Nunc dictum finibus condimentum. Ut faucibus at nulla a ultrices. In mollis interdum volutpat. In id augue vitae sem euismod faucibus. Praesent tempor et dolor a mattis. Morbi massa mi, finibus quis neque quis, posuere egestas risus. Praesent non pharetra risus. Ut et pharetra velit. Nam eu dictum nunc, nec molestie purus. Pellentesque justo orci, convallis varius posuere sit amet, maximus at orci. Praesent sit amet justo quis felis pulvinar pellentesque. Maecenas ut mi eget nulla aliquet hendrerit. Etiam malesuada nibh eu felis sodales, ut congue libero malesuada.

Nam suscipit fermentum nisi at faucibus. Cras suscipit vel lacus ut interdum. Aliquam maximus volutpat augue eget eleifend. Suspendisse tellus diam, consectetur et tempor quis, tincidunt sit amet nibh. Suspendisse eget sagittis dui. Nunc dignissim porttitor blandit. Sed a viverra ligula, vitae ullamcorper velit. In vestibulum dolor vitae leo posuere blandit. Nullam tellus neque, elementum non quam in, porttitor dignissim nibh. Nullam ultrices magna ut porta pellentesque. Etiam luctus ac quam eu pellentesque. Donec euismod magna id odio dignissim, sed volutpat turpis facilisis. Phasellus ultricies viverra sagittis. Morbi commodo placerat mi vel dapibus.

Cras sit amet augue malesuada, elementum ipsum tempor, volutpat erat. Fusce orci quam, faucibus non sem nec, tempor ultrices nibh. Mauris non pharetra leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur vitae blandit arcu. Nunc turpis ipsum, bibendum quis sodales sed, ullamcorper et lorem. Donec et metus hendrerit, interdum elit ut, dignissim dui. Aliquam eleifend leo non ullamcorper elementum. Fusce nibh erat, pellentesque sit amet ante non, tempus viverra nisi. Sed aliquam nisi mi, sed commodo risus pretium quis. Aliquam ac bibendum ligula, a dictum dui. Ut dui urna, pharetra eu arcu non, aliquam accumsan libero. Nulla volutpat lacus augue, a pellentesque elit congue eget. Morbi nec accumsan urna.';


	public function testCorrectSnippets()
	{
		$snippet = new TextSnippet();
		$result = $snippet->createSnippet('Lorem', $this->lorumIpsum);
		$this->assertEquals('<span class="highlighted">Lorem</span> ipsum dolor sit amet, consectetur adipiscing elit. ... Etiam bibendum <span class="highlighted">lorem</span> nec tempus sollicitudin. ... Sed in dapibus <span class="highlighted">lorem</span>. ... Nunc turpis ipsum, bibendum quis sodales sed, ullamcorper et <span class="highlighted">lorem</span>. Donec et metus hendrerit, interdum elit ut, dignissim dui.', $result);
	}


	public function testCorrectHighlight()
	{
		$snippet = new TextSnippet();
		$snippet->setHighlightTemplate('<test>%word%</test>');
		$result = $snippet->createSnippet('ultrices', $this->lorumIpsum);

		$this->assertEquals('Ut faucibus at nulla a <test>ultrices</test>. ... Nullam <test>ultrices</test> magna ut porta pellentesque. ... Fusce orci quam, faucibus non sem nec, tempor <test>ultrices</test> nibh. Mauris non pharetra leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', $result);
	}


	public function testCorrectDisabledHighlight()
	{
		$snippet = new TextSnippet();
		$result = $snippet->createSnippet('ultrices', $this->lorumIpsum, false);
		$this->assertEquals('Ut faucibus at nulla a ultrices. ... Nullam ultrices magna ut porta pellentesque. ... Fusce orci quam, faucibus non sem nec, tempor ultrices nibh. Mauris non pharetra leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', $result);
	}


	public function testWordVariableIsRequired()
	{
		$this->expectException('RuntimeException');

		$snippet = new TextSnippet();
		$snippet->setHighlightTemplate('my broken template');
	}


	public function minMaxWordsProvider()
	{
		return [
			[10, 30],
			[5, 10],
			[30, 40],
			[50, 60],
		];
	}


	/**
	 * @dataProvider minMaxWordsProvider
	 * @param $minWords
	 * @param $maxWords
	 */
	public function testMinAndMax($minWords, $maxWords)
	{
		$snippet = new TextSnippet();
		$snippet->setMinWords($minWords);
		$snippet->setMaxWords($maxWords);
		$result = $snippet->createSnippet('urna', $this->lorumIpsum, false);
		$this->assertGreaterThan($minWords, str_word_count($result, 0, implode('', $this->specialChars)));
		$this->assertLessThan($maxWords, str_word_count($result, 0, implode('', $this->specialChars)));
	}
}
