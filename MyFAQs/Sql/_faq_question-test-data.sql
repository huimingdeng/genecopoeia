/*
Navicat MySQL Data Transfer

Source Server         : user-root@localhost@phpStudy2018
Source Server Version : 50725
Source Host           : localhost:3306
Source Database       : cn_web

Target Server Type    : MYSQL
Target Server Version : 50725
File Encoding         : 65001

Date: 2019-03-25 16:45:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for _faq_question
-- ----------------------------
DROP TABLE IF EXISTS `_faq_question`;
CREATE TABLE `_faq_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'issue',
  `answer` text NOT NULL COMMENT 'answer',
  `pubdate` datetime NOT NULL,
  `editdate` datetime NOT NULL,
  `category` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `issue` (`title`),
  KEY `catagory_id` (`category`),
  CONSTRAINT `category_id` FOREIGN KEY (`category`) REFERENCES `_faq_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _faq_question
-- ----------------------------
INSERT INTO `_faq_question` VALUES ('1', '我能用基因组编辑技术在基因组上引入一个点突变吗？', '能。对于这种基因组编辑操作，您需要带有靶点同源重组臂以及所需点突变的供体 DNA。供体 DNA 可以是一个质粒或一条单链的寡聚核苷酸。供体 DNA 需要与基因组编辑工具（TALEN 或 CRISPR）进行共转染。基因组编辑工具在靶点生成的 DNA 双链断裂（DSB），细胞在存在供体 DNA 的情况下，会将供体 DNA 作为修复模板，利用同源重组（HR）机制修复 DSB。', '2019-03-19 09:20:20', '2019-03-21 05:20:09', '4');
INSERT INTO `_faq_question` VALUES ('2', '为什么要用同源重组（HR）方法做基因组编辑？', '答：用同源重组（HR）方法做基因组编辑有几大理由：1）HR诱导的基因组编辑具有精确性和可控性，可以实现几乎任意基因序列操作；2）您可以通过同时敲入筛选标记（如抗性基因或荧光报告基因），大大提高筛选阳性克隆的简便性。\r\n\r\n易锦提供供体质粒设计及构建服务。我们能构建带有目的修饰及靶序列同源重组臂的供体质粒。质粒同时会敲入筛选标记（如嘌呤霉素抗性基因）或 copGFP 报告基因。这些筛选标记两端可带有 loxP 重组位点，可以在需要时通过 Cre-loxP 重组从基因组上去掉筛选标记序列。使用易锦提供的供体质粒，能减少筛选的克隆数，方便挑出所需的修饰阳性细胞系', '2019-03-20 03:18:21', '2019-03-21 05:20:21', '4');
INSERT INTO `_faq_question` VALUES ('3', 'What species are your products and services aimed at?', '答：我们的基因组编辑服务实际上可以适用于任何物种。我们的 TAL 效应子和 CRISPR 质粒的载体适用于哺乳动物细胞。此外，这些质粒也可以用于 T7 启动子启动的体外转录，转录出的 mRNA 可用于转染小鼠、斑马鱼、果蝇以及其他更多的模式生物。易锦也提供基因组编辑克隆定制服务，为客户定制适用于其他生物的 TAL 效应子或 CRISPR 体系克隆。', '2019-03-20 05:09:10', '2019-03-21 05:20:29', '4');
INSERT INTO `_faq_question` VALUES ('10', '供体 DNA 是不是必须得跟 TALEN 或 CRISPR 一起进行转染？', '答：是的。DNA 双链断裂（DSB）发生时供体 DNA 必须同时存在于细胞内才会被用作同源重组（HR）模板。 否则，细胞会用非同源末端链接（NHEJ）修复 DSB。', '2019-03-20 05:14:28', '2019-03-21 05:20:41', '4');
INSERT INTO `_faq_question` VALUES ('18', 'test', '回答：Invitrogen公司有一系列的pDEST表达载体（目标载体）和ORFEXPRESS™ 穿梭克隆相匹配，这些载体可以在细菌、酵母、昆虫和哺乳动物细胞等不同的宿主中表达各种类型   的蛋白（如天然的、N-端或C-段的融合蛋白）。', '2019-03-20 09:14:24', '2019-03-21 05:20:53', '4');
INSERT INTO `_faq_question` VALUES ('19', 'GeneCopoeia提供其他物种的ORF克隆吗？', '回答：我们目前提供已构建的人、小鼠ORF克隆。我们正要推出其他模式动物（如大鼠）的全长ORF克隆。如果您拥有感兴趣的模式动物基因的全长ORF序列，请提供给我们这些基因的基本信息（GenBank Accession或核酸序列），我们会优先将这些基因克隆到载体上。收取的费用和目录上的ORF克隆一样。我们还提供将人、小鼠基因克隆到特定载体的定制服务。', '2019-03-20 09:17:14', '2019-03-21 05:21:01', '4');
INSERT INTO `_faq_question` VALUES ('20', '我能获得双等位基因修饰吗？', '答：能。TALEN 和 CRISPR 都有同时编辑多个拷贝的能力。编辑的效率受到不同因素影响，如细胞类型、转染效率和 CRISPR/TALEN 的活性。', '2019-03-21 07:01:23', '2019-03-21 07:01:23', '4');
INSERT INTO `_faq_question` VALUES ('21', '我该选 TALEN 还是 CRISPR？', '答：视乎您的实验目的和需要。TALEN 和 CRISPR 体系各有各的优缺点。CRISPR 体系编辑基因的效率一般要高于 TALEN，且 CRISPR 对甲基化不敏感，更适用于多靶点编辑，这些方面对比 TALEN 优势确实相当明显。但另一方面，CRISPR，包括 Cas9 切口酶在内，都更容易有脱靶效应。视乎实验目的，这对于某些实验应用来说是个影响较大的问题。', '2019-03-21 07:03:18', '2019-03-21 07:03:18', '4');
INSERT INTO `_faq_question` VALUES ('22', 'TALEN 或 CRISPR 会在基因被编辑成功后继续切割染色体序列吗？', '答：我们的 TAL 效应子或 CRISPR 载体是设计成不会宿主体内复制或整合到宿主基因组的类型。这些质粒被瞬时转染后，通常会在几轮细胞分裂后丢失，不会进一步影响宿主细胞。在转染后，细胞被低密度涂布，促进单克隆群落的生成。这些群落应进行检测，确保质粒已经丢失。检测可以采取抗性筛选的方法，观察细胞是否对质粒抗性基因对应的抗生素敏感；或者观察质粒的荧光报告基因是否不再表达。我们建议使用 mRNA 而不是 DNA 进行转染，来确保转染的瞬时性。', '2019-03-21 07:04:37', '2019-03-21 07:04:37', '4');
INSERT INTO `_faq_question` VALUES ('23', '我可以选择使用切口酶而不是野生型的 Cas9 核酸酶吗？', '答：Yes. 可以。我们也提供 Cas9 D10A 切口酶的表达克隆，也成功测试过双切口酶方法。要使用切口酶生成 DNA 双链断裂（DSB），需要使两条间距恰当的 sgRNA 正确地分别靶向断裂位点两侧的双链。此外，sgRNA 针对的靶点序列3端需要紧接有“N-G-G” PAM 序列，因此，不是每个位点的序列都合适双切口酶方法。', '2019-03-21 07:05:29', '2019-03-21 07:05:29', '1');
INSERT INTO `_faq_question` VALUES ('24', '我打算做同源重组（HR）的话，可以使用双切口酶方法吗？ ', '答：可以。要使用切口酶生成 DNA 双链断裂（DSB），需要使两条间距恰当的 sgRNA 正确地分别靶向断裂位点两侧的双链。生成的 DSB 足够诱导靶点与供体克隆之间发生同源重组（HR）。这个方法好处是降低潜在的脱靶效应（脱靶造成的单链切口基本能被修复，不会诱发非同源末端链接引起插入确实突变），但并非没有限制。sgRNA 针对的靶点序列3端需要紧接有“N-G-G” PAM 序列，因此，不是每个位点的序列都合适双切口酶方法。', '2019-03-21 07:06:36', '2019-03-21 07:06:36', '1');
INSERT INTO `_faq_question` VALUES ('25', '我能订购空白的 sgRNA 载体吗？', '答：我们只提供装载好客户定制的 sgRNA 的 CRISPR 质粒。如果您需要阴性对照，我们可以提供装有乱序 sgRNA 的 CRISPR 克隆。', '2019-03-21 07:07:29', '2019-03-21 07:07:29', '1');
INSERT INTO `_faq_question` VALUES ('26', '你们有可以识别甲基化C（胞嘧啶）的TAL 效应子模块吗？ ', '有。', '2019-03-21 07:09:15', '2019-03-21 07:09:15', '7');
INSERT INTO `_faq_question` VALUES ('27', '我能订购空白的供体载体来 DIY 供体质粒吗？ ', '能。', '2019-03-21 07:10:09', '2019-03-21 07:10:09', '1');
INSERT INTO `_faq_question` VALUES ('28', '我能用 CRISPR 做基因表达激活或表达抑制吗？', '答：能。Cas9 有一种双位点突变体完全不带有核酸酶活性。这种突变体可以与一个转录调控因子（如VP64）融合，靶向特定的基因。您可以使用这种失活的 Cas9 和设计好的 sgRNA 配合用于抑制或干扰基因的转录。', '2019-03-21 07:10:35', '2019-03-21 07:10:35', '1');
INSERT INTO `_faq_question` VALUES ('29', '你们的 CRISPR 可以装慢病毒载体吗？', '答：可以。我们有非慢病毒型和慢病毒型两种载体。我们也提供慢病毒颗粒包装服务，为您提供表达 Cas9 和 sgRNA 的慢病毒颗粒。', '2019-03-21 07:11:24', '2019-03-21 07:11:24', '1');
INSERT INTO `_faq_question` VALUES ('30', '我能用慢病毒方法导入 CRISPR 和供体克隆做同源重组（HR）吗？', '答：很遗憾，这个方法对同源重组（HR）行不通。慢病毒以 RNA 的形式进入细胞，但 HR 的供体克隆需要以 DNA 的形式与 Cas9 及 sgRNA 同时进入细胞。', '2019-03-21 07:14:15', '2019-03-21 07:14:15', '1');
INSERT INTO `_faq_question` VALUES ('31', 'Safe Harbor 可以用 CRISPR 做吗?', '答：可以。易锦提供 TALEN 型和 CRISPR 型的 Safe Harbor 试剂盒，可以靶向人类 AAVS1 位点或者小鼠 ROSA26 位点。这些试剂盒能完全兼容我们的 Safe Harbor ORF 敲入克隆。', '2019-03-21 07:16:53', '2019-03-21 07:16:53', '8');
INSERT INTO `_faq_question` VALUES ('32', 'How do I order ORF clones from GeneCopoeia?', 'Answer: All you need to do is go to the ORF clones search page, search for your gene, and then choose the appropriate clones that will work for your system. If you have any custom requirements, then you will need to contact us and, after determining what you need, we will send you a custom quote.', '2019-03-21 07:47:56', '2019-03-21 07:47:56', '6');
INSERT INTO `_faq_question` VALUES ('33', 'test2', '<a href=\"mailto:support@genecopoeia.com\">support@genecopoeia.com</a>', '2019-03-21 08:43:09', '2019-03-21 08:43:09', '4');
INSERT INTO `_faq_question` VALUES ('34', 'What delivery formats do you offer for your ORF clones?', 'Answer: We provide most ORF clones as 10 µg of purified plasmid. For our NextDay™ clones, you can choose between bacterial stock or transfection-ready DNA (10 µg or 50 µg). You can also request alternative delivery formats by contacting us at <a href=\"mailto:support@genecopoeia.com\">support@genecopoeia.com</a>', '2019-03-21 08:44:53', '2019-03-21 08:44:53', '6');
INSERT INTO `_faq_question` VALUES ('35', 'What is a “NextDay™” ORF clone?', 'Answer: A NextDay™ ORF clone is a pre-made ORF clone. It is available at a competitive and ships next day if the order is placed by noon Eastern Time.', '2019-03-21 08:47:24', '2019-03-21 08:47:24', '1');
INSERT INTO `_faq_question` VALUES ('36', 'test3', 'test', '2019-03-22 08:41:44', '2019-03-22 08:43:12', '2');
