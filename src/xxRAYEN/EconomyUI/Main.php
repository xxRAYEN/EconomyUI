<?php

namespace xxRAYEN\EconomyUI;

use pocketmine\plugin\{
	PluginBase,
	Plugin
};
use pocketmine\event\{
	Listener,
	player\PlayerJoinEvent
};
use pocketmine\command\{
	Command,
	CommandSender
};
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onJoin(PlayerJoinEvent $event) {
		$sender = $event->getPlayer();
		if(1 >= $this->getConfig()->get($sender->getName())){
			$this->getConfig()->set($sender->getName(), 1000);
			$this->getConfig()->save();
		}
	}
	
	public function onCommand(CommandSender $sender, Command $command, $lbl, array $args) : bool
	{
		switch($command->getName()){
			case "bank":
				if($this->getConfig()->get("lang") == "german") {
					$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $data) {
						switch($data) {
							case 0:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cDazu hast du keine Berechtigung!";
								$sender->sendMessage($start . "§aErfolgreich geschlossen!");
								break;
							case 1:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cDazu hast du keine Berechtigung!";
								$form2 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data2) {
									if($this->getConfig()->get($data2[1]) >= 1) {
										if($this->getConfig()->get($sender->getName()) - 5 >= $data2[2]) {
											if($this->getConfig()->get($sender->getName()) >= 1) {
												if($data2[2] >= 1) {
													if($data2[1] == $sender->getName()) {
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§cDu kannst kein Geld an dich selber überweisen!");
													} else {
														$this->getConfig()->set($sender->getName(), $this->getConfig()->get($sender->getName()) - $data2[2]);
														$this->getConfig()->set($data2[1], $this->getConfig()->get($data2[1]) + $data2[2]);
														$this->getConfig()->save();
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§aÜberweisung Erfolgreich!");
													}
												} else {
													$start = "§7[ §b§lSYSTEM §r§7] ";
													$sender->sendMessage($start . "§cDu musst mindestens 1 Euro überweisen!");
												}
											} else {
												$start = "§7[ §b§lSYSTEM §r§7] ";
												$sender->sendMessage($start . "§cEin Fehler ist aufgetreten!");
											}
										} else {
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$sender->sendMessage($start . "§cDazu hast du nicht genug Geld! Du musst nach der Überweisung mindestens 5 Euro haben!");
										}
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cDieser Spieler existiert nicht!");
									}
								});
								$form2->setTitle("§aGeld Überweisen");
								$form2->addLabel("§7Überweise §aGeld §7zu anderen §cSpielern§7.");
								$form2->addInput("§cSpielername:", "Spielername", "");
								$form2->addInput("§aGeldmenge:", "Geldmenge", "");
								$form2->sendToPlayer($sender);
								break;
							case 2:
								$sendername = $sender->getName();
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cDazu hast du keine Berechtigung!";
								$money = $this->getConfig()->get($sendername);
								$form3 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $data3) {
									switch($data3) {
										case 0:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$noperm = "§cDazu hast du keine Berechtigung!";
											$sender->sendMessage($start . "§aErfolgreich geschlossen!");
											break;
									}
								});
								$form3->setTitle("§a§lKONTOSTAND");
								$form3->setContent("§7Der Spieler §c" . $sender->getName() . " §7hat insgesamt §a" . $money . " §7Euro auf seinem Konto.");
								$form3->addButton("§6Okay");
								$form3->sendToPlayer($sender);
								break;
							case 3:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cDazu hast du keine Berechtigung!";
								$form4 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data4) {
									if($this->getConfig()->get($data4[1]) >= 1) {
										if($this->getConfig()->get($data4[1]) - 5 >= $data4[2]) {
											if($this->getConfig()->get($sender->getName()) >= 1) {
												if($data4[2] >= 1) {
													if($data4[1] == $sender->getName()) {
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§cDu kannst kein Geld von dir selber nehmen!");
													} else {
														$this->getConfig()->set($sender->getName(), $this->getConfig()->get($sender->getName()) + $data4[2]);
														$this->getConfig()->set($data4[1], $this->getConfig()->get($data4[1]) - $data4[2]);
														$this->getConfig()->save();
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§aÜberweisung Erfolgreich!");
													}
												} else {
													$start = "§7[ §b§lSYSTEM §r§7] ";
													$sender->sendMessage($start . "§cDu musst mindestens 1 Euro nehmen!");
												}
											} else {
												$start = "§7[ §b§lSYSTEM §r§7] ";
												$sender->sendMessage($start . "§cEin Fehler ist aufgetreten!");
											}
										} else {
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$sender->sendMessage($start . "§cDazu hat der Spieler nicht genug Geld! Er muss nach der Überweisung mindestens 5 Euro haben!");
										}
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cDieser Spieler existiert nicht!");
									}
								});
								$form4->setTitle("§aGeld nehmen");
								$form4->addLabel("§7Nehme §aGeld §7von anderen §cSpielern§7.");
								$form4->addInput("§cSpielername:", "Spielername", "");
								$form4->addInput("§aGeldmenge:", "Geldmenge", "");
								$form4->sendToPlayer($sender);
								break;
							case 4:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cDazu hast du keine Berechtigung!";
								$form5 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data5) {
									if($this->getConfig()->get($data5[1]) >= 1) {
										$this->getConfig()->set($data5[1], $this->getConfig()->get($data5[1]) + $data5[2]);
										$this->getConfig()->save();
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§aÜberweisung Erfolgreich!");
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cDu musst mindestens 1 Euro geben!");
									}
								});
								$form5->setTitle("§aGeld geben");
								$form5->addLabel("§7Gebe §aGeld §7von der Konsole an §cSpielern§7.");
								$form5->addInput("§cSpielername:", "Spielername", "");
								$form5->addInput("§aGeldmenge:", "Geldmenge", "");
								$form5->sendToPlayer($sender);
								break;
							case 5:
								$formLANG = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $dataLANG) {
									switch($dataLANG) {
										case 0:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$noperm = "§cDazu hast du keine Berechtigung!";
											$sender->sendMessage($start . "§aErfolgreich geschlossen!");
											break;
										case 1:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "english");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aSuccessfully set to the §cEnglish §alanguage!");
											break;
										case 2:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "german");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aErfolgreich auf die Sprache §cDeutsch §agestellt!");
											break;
										case 3:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "french");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aDéfini avec succès sur le §cfrançais§a!");
											break;
									}
								});
								$formLANG->setTitle("§b§lSPRACHE");
								$formLANG->setContent("§7Setze deine Server §bSprache §7hier:");
								$formLANG->addButton("§4§lABBRECHEN");
								$formLANG->addButton("§bEnglisch");
								$formLANG->addButton("§bDeutsch");
								$formLANG->addButton("§bFranzösisch");
								$formLANG->sendToPlayer($sender);
						}
					});
					$form->setTitle("§a§lBANK");
					$form->setContent("§7Deine Persönliche §aBank§7.");
					$form->addButton("§4§lABBRECHEN");
					$form->addButton("§aGeld Überweisen");
					$form->addButton("§6Kontostand");
					if($sender->hasPermission("money.take")) {
						$form->addButton("§cPrivat §rGeld nehmen");
					}
					if($sender->hasPermission("money.give")) {
						$form->addButton("§cPrivat §rGeld hinzufügen");
					}
					if($sender->isOP()) {
						$form->addButton("§cPrivat §rSprache setzen");
					}
					if($sender instanceof Player) {
						$form->sendToPlayer($sender);
					} else {
						$sender->sendMessage("Du kannst das Bankmenü nicht über die Console öffnen!");
					}
				} elseif($this->getConfig()->get("lang") == "english") {
					$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $data) {
						switch($data) {
							case 0:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cYou haven't enough permissions for that!";
								$sender->sendMessage($start . "§aClosed successfully!");
								break;
							case 1:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cYou haven't enough permissions for that!";
								$form2 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data2) {
									if($this->getConfig()->get($data2[1]) >= 1) {
										if($this->getConfig()->get($sender->getName()) - 5 >= $data2[2]) {
											if($this->getConfig()->get($sender->getName()) >= 1) {
												if($data2[2] >= 1) {
													if($data2[1] == $sender->getName()) {
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§cYou cannot transfer money to yourself!");
													} else {
														$this->getConfig()->set($sender->getName(), $this->getConfig()->get($sender->getName()) - $data2[2]);
														$this->getConfig()->set($data2[1], $this->getConfig()->get($data2[1]) + $data2[2]);
														$this->getConfig()->save();
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§aTransfer successful!");
													}
												} else {
													$start = "§7[ §b§lSYSTEM §r§7] ";
													$sender->sendMessage($start . "§cYou have to transfer 1 euro or more!");
												}
											} else {
												$start = "§7[ §b§lSYSTEM §r§7] ";
												$sender->sendMessage($start . "§cAn error has occurred!");
											}
										} else {
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$sender->sendMessage($start . "§cYou haven't enough money for that! After this transfer you must have 5 euro or more!");
										}
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cThis player doesn't exist!");
									}
								});
								$form2->setTitle("§aPay money");
								$form2->addLabel("§7Pay §amoney §7to other §cPlayers§7.");
								$form2->addInput("§cPlayername:", "Playername", "");
								$form2->addInput("§aMoney amount:", "Money amount", "");
								$form2->sendToPlayer($sender);
								break;
							case 2:
								$sendername = $sender->getName();	
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cYou haven't enough permissions for that!";
								$money = $this->getConfig()->get($sendername);
								$form3 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $data3) {
									switch($data3) {
										case 0:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$noperm = "§cYou haven't enough permissions for that!";
											$sender->sendMessage($start . "§aClosed successfully!");
											break;
									}
								});
								$form3->setTitle("§a§lBalance");
								$form3->setContent("§7The player §c" . $sender->getName() . " §7has §a" . $money . " §7euro as his balance.");
								$form3->addButton("§6Okay");
								$form3->sendToPlayer($sender);
								break;
							case 3:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cYou haven't enough permissions for that!";
								$form4 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data4) {
									if($this->getConfig()->get($data4[1]) >= 1) {
										if($this->getConfig()->get($data4[1]) - 5 >= $data4[2]) {
											if($this->getConfig()->get($sender->getName()) >= 1) {
												if($data4[2] >= 1) {
													if($data4[1] == $sender->getName()) {
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§cYou can't take money from yourself!");
													} else {
														$this->getConfig()->set($sender->getName(), $this->getConfig()->get($sender->getName()) + $data4[2]);
														$this->getConfig()->set($data4[1], $this->getConfig()->get($data4[1]) - $data4[2]);
														$this->getConfig()->save();
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§aTransferred successfully!");
													}
												} else {
													$start = "§7[ §b§lSYSTEM §r§7] ";
													$sender->sendMessage($start . "§cYou must take 1 euro or more!");
												}
											} else {
												$start = "§7[ §b§lSYSTEM §r§7] ";
												$sender->sendMessage($start . "§cAn error has occurred!");
											}
										} else {
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$sender->sendMessage($start . "§cThe player doesn't enough money for this! He must has, after the tranfer, 5 euro or more!");
										}
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cThis player doesn't exist!");
									}
								});
								$form4->setTitle("§aTake money");
								$form4->addLabel("§7Take §amoney §7from other §cplayers§7.");
								$form4->addInput("§cPlayername:", "Playername", "");
								$form4->addInput("§aMoney amount:", "Money amount", "");
								$form4->sendToPlayer($sender);
								break;
							case 4:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cYou haven't enough permissions for that!";
								$form5 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data5) {
									if($this->getConfig()->get($data5[1]) >= 1) {
										if($data5[2] >= 1) {
											$this->getConfig()->set($data5[1], $this->getConfig()->get($data5[1]) + $data5[2]);
											$this->getConfig()->save();
											$start = "§7[ §b§lSYSTEM §r§7] ";	
											$sender->sendMessage($start . "§aTransferred successfully!");
										} else {
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$sender->sendMessage($start . "§cYou must give 1 euro or more!");
										}
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cThis player doesn't exist!");
									}
								});
								$form5->setTitle("§aGive money");
								$form5->addLabel("§7Give §amoney §7from the console to §cplayer§7.");
								$form5->addInput("§cPlayername:", "Playername", "");
								$form5->addInput("§aMoney amount:", "Money amount", "");
								$form5->sendToPlayer($sender);
								break;
							case 5:
								$formLANG = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $dataLANG) {
									switch($dataLANG) {
										case 0:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$noperm = "§cDazu hast du keine Berechtigung!";
											$sender->sendMessage($start . "§aClosed successfully!");
											break;
										case 1:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "english");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aSuccessfully set to the §cEnglish §alanguage!");
											break;
										case 2:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "german");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aErfolgreich auf die Sprache §cDeutsch §agestellt!");
											break;
										case 3:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "french");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aDéfini avec succès sur le §cfrançais§a!");
											break;
									}
								});
								$formLANG->setTitle("§b§lLANGUAGE");
								$formLANG->setContent("§7Set your server §blanguage §7here:");
								$formLANG->addButton("§4§lCANCEL");
								$formLANG->addButton("§bEnglish");
								$formLANG->addButton("§bGerman");
								$formLANG->addButton("§bFrench");
								$formLANG->sendToPlayer($sender);
						}
					});
					$form->setTitle("§a§lBANK");
					$form->setContent("§7Your personal §abank§7.");
					$form->addButton("§4§lCANCEL");
					$form->addButton("§aPay money");
					$form->addButton("§6Balance");
					if($sender->hasPermission("money.take")) {
						$form->addButton("§cPrivate §rTake money");
					}
					if($sender->hasPermission("money.give")) {
						$form->addButton("§cPrivate §rGive money");
					}
					if($sender->isOP()) {
						$form->addButton("§cPrivate §rSet language");
					}
					if($sender instanceof Player) {
						$form->sendToPlayer($sender);
					} else {
						$sender->sendMessage("You can't open the bank menu on the console!");
					}
				} elseif($this->getConfig()->get("lang") == "french") {
					$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $data) {
						switch($data) {
							case 0:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cVous n'avez pas assez d'autorisations pour cela!";
								$sender->sendMessage($start . "§aFermé avec succès!");
								break;
							case 1:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cVous n'avez pas assez d'autorisations pour cela!";
								$form2 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data2) {
									if($this->getConfig()->get($data2[1]) >= 1) {
										if($this->getConfig()->get($sender->getName()) - 5 >= $data2[2]) {
											if($this->getConfig()->get($sender->getName()) >= 1) {
												if($data2[2] >= 1) {
													if($data2[1] == $sender->getName()) {
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§cVous ne pouvez pas vous transférer d'argent!");
													} else {
														$this->getConfig()->set($sender->getName(), $this->getConfig()->get($sender->getName()) - $data2[2]);
														$this->getConfig()->set($data2[1], $this->getConfig()->get($data2[1]) + $data2[2]);
														$this->getConfig()->save();
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§aTransfert réussi!");
													}
												} else {
													$start = "§7[ §b§lSYSTEM §r§7] ";
													$sender->sendMessage($start . "§cVous devez transférer 1 euro ou plus!");
												}
											} else {
												$start = "§7[ §b§lSYSTEM §r§7] ";
												$sender->sendMessage($start . "§cUne erreur est survenue!");
											}
										} else {
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$sender->sendMessage($start . "§cVous n'avez pas assez d'argent pour ça! Après ce transfert, vous devez avoir 5 euros ou plus!");
										}
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cCe joueur n'existe pas!");
									}
								});
								$form2->setTitle("§aVerser de l'argent");
								$form2->addLabel("§7Payer de §al'argent §7à d'autres §cjoueurs§7.");
								$form2->addInput("§cNom de joueur:", "Nom de joueur", "");
								$form2->addInput("§aMontant d'argent:", "Montant d'argent", "");
								$form2->sendToPlayer($sender);
								break;
							case 2:
								$sendername = $sender->getName();	
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cVous n'avez pas assez d'autorisations pour cela!";
								$money = $this->getConfig()->get($sendername);
								$form3 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $data3) {
									switch($data3) {
										case 0:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$noperm = "§cVous n'avez pas assez d'autorisations pour cela!";
											$sender->sendMessage($start . "§aFermé avec succès!");
											break;
									}
								});
								$form3->setTitle("§a§lÉquilibre");
								$form3->setContent("§7Le joueur §c" . $sender->getName() . " §7a §a" . $money . " §7euro comme solde.");
								$form3->addButton("§6D'accord");
								$form3->sendToPlayer($sender);
								break;
							case 3:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cVous n'avez pas assez d'autorisations pour cela!";
								$form4 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data4) {
									if($this->getConfig()->get($data4[1]) >= 1) {
										if($this->getConfig()->get($data4[1]) - 5 >= $data4[2]) {
											if($this->getConfig()->get($sender->getName()) >= 1) {
												if($data4[2] >= 1) {
													if($data4[1] == $sender->getName()) {
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§cVous ne pouvez pas vous prendre de l'argent!");
													} else {
														$this->getConfig()->set($sender->getName(), $this->getConfig()->get($sender->getName()) + $data4[2]);
														$this->getConfig()->set($data4[1], $this->getConfig()->get($data4[1]) - $data4[2]);
														$this->getConfig()->save();
														$start = "§7[ §b§lSYSTEM §r§7] ";
														$sender->sendMessage($start . "§aTransféré avec succès!");
													}
												} else {
													$start = "§7[ §b§lSYSTEM §r§7] ";
													$sender->sendMessage($start . "§cVous devez prendre 1 euro ou plus!");
												}
											} else {
												$start = "§7[ §b§lSYSTEM §r§7] ";
												$sender->sendMessage($start . "§cUne erreur est survenue!");
											}
										} else {
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$sender->sendMessage($start . "§cLe joueur n'a pas assez d'argent pour ça! Il doit avoir, après le transfert, 5 euros ou plus!");
										}
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cCe joueur n'existe pas!");
									}
								});
								$form4->setTitle("§aPrend l'argent");
								$form4->addLabel("§7Prendre de §al'argent §7à d'autres §cjoueurs§7.");
								$form4->addInput("§cNom de joueur:", "Nom de joueur", "");
								$form4->addInput("§aMontant d'argent:", "Montant d'argent", "");
								$form4->sendToPlayer($sender);
								break;
							case 4:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$noperm = "§cVous n'avez pas assez d'autorisations pour cela!";
								$form5 = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function ($sender, $data5) {
									if($this->getConfig()->get($data5[1]) >= 1) {
										if($data5[2] >= 1) {
											$this->getConfig()->set($data5[1], $this->getConfig()->get($data5[1]) + $data5[2]);
											$this->getConfig()->save();
											$start = "§7[ §b§lSYSTEM §r§7] ";	
											$sender->sendMessage($start . "§aTransféré avec succès!");
										} else {
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$sender->sendMessage($start . "§cVous devez donner 1 euro ou plus!");
										}
									} else {
										$start = "§7[ §b§lSYSTEM §r§7] ";
										$sender->sendMessage($start . "§cCe joueur n'existe pas!");
									}
								});
								$form5->setTitle("§aDonner de l'argent");
								$form5->addLabel("§7Donner de §cl'argent §7de la console au §cjoueur§7.");
								$form5->addInput("§cNom de joueur:", "Nom de joueur", "");
								$form5->addInput("§aMontant d'argent:", "Montant d'argent", "");
								$form5->sendToPlayer($sender);
								break;
							case 5:
								$formLANG = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $dataLANG) {
									switch($dataLANG) {
										case 0:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$noperm = "§cVous n'êtes pas autorisé à le faire!";
											$sender->sendMessage($start . "§aFermé avec succès!");
											break;
										case 1:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "english");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aSuccessfully set to the §cEnglish §alanguage!");
											break;
										case 2:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "german");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aErfolgreich auf die Sprache §cDeutsch §agestellt!");
											break;
										case 3:
											$start = "§7[ §b§lSYSTEM §r§7] ";
											$this->getConfig()->set("lang", "french");
											$this->getConfig()->save();
											$sender->sendMessage($start . "§aDéfini avec succès sur le §cfrançais§a!");
											break;
									}
								});
								$formLANG->setTitle("§b§lLANGUE");
								$formLANG->setContent("§7Définissez la §blangue §7de votre serveur ici:");
								$formLANG->addButton("§4§lANNULER");
								$formLANG->addButton("§bAnglaise");
								$formLANG->addButton("§bAllemande");
								$formLANG->addButton("§bFrançais");
								$formLANG->sendToPlayer($sender);
						}
					});
					$form->setTitle("§a§lBANQUE");
					$form->setContent("§7Votre §abanque personnelle§7.");
					$form->addButton("§4§lANNULER");
					$form->addButton("§aVerser de l'argent");
					$form->addButton("§6Équilibre");
					if($sender->hasPermission("money.take")) {
						$form->addButton("§cPrivée §rPrend l'argent");
					}
					if($sender->hasPermission("money.give")) {
						$form->addButton("§cPrivée §rDonner de l'argent");
					}
					if($sender->isOP()) {
						$form->addButton("§cPrivée §rDéfinir la langue");
					}
					if($sender instanceof Player) {
						$form->sendToPlayer($sender);
					} else {
						$sender->sendMessage("Vous ne pouvez pas ouvrir le menu de la banque sur la console!");
					}
				} else {
					$formLANG = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function ($sender, $dataLANG) {
						switch($dataLANG) {
							case 0:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$this->getConfig()->set("lang", "english");
								$this->getConfig()->save();
								$sender->sendMessage($start . "§aSuccessfully set to the §cEnglish §alanguage!");
								break;
							case 1:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$this->getConfig()->set("lang", "german");
								$this->getConfig()->save();
								$sender->sendMessage($start . "§aErfolgreich auf die Sprache §cDeutsch §agestellt!");
								break;
							case 2:
								$start = "§7[ §b§lSYSTEM §r§7] ";
								$this->getConfig()->set("lang", "french");
								$this->getConfig()->save();
								$sender->sendMessage($start . "§aDéfini avec succès sur le §cfrançais§a!");
								break;
						}
					});
					if($sender->isOP()) {
						$formLANG->setTitle("§cLANGUAGE");
						$formLANG->setContent("§7Set your server §clanguage §7here:");
						$formLANG->addButton("§bEnglish");
						$formLANG->addButton("§bDeutsch");
						$formLANG->addButton("§bFranzösisch");
						$formLANG->sendToPlayer($sender);
					} else {
						$this->getConfig()->set("lang", "english");
						$this->getConfig()->save();
						$sender->sendMessage($start . "§cPlease try it again!");
					}
				}
				return true;
		}
		return true;
	}
}