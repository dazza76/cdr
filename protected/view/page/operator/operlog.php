<?php
/**
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this OperatorController */

?>

                <div class="filters clear_fix">
                    <form method="get" action="" class="of_h">
                        <input type="hidden" name="section" value="operlog" />
                        <div class="filter fl_l sep">
                            <div class="label">Дата</div>
                            <div class="labeled">
                                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                                —
                                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->todate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                            </div>
                        </div>

                        <div class="filter fl_l sep">
                            <div class="label">Оператор</div>
                            <div class="labeled">
                                <select name="oper" size="1"  default="<?php echo $this->oper; ?>">
                                    <?php echo QueueAgent::showOperslist(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="filter fl_l">
                            <div class="labeled">
                                <input type="submit" name="search" id="button-search" class="button button-search" class="button" value="Показать" />
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="" />
                        <input type="hidden" name="desc" value="" />
                        <input type="hidden" name="offset" value="" />
                    </form>
                </div>

                <div class="clear clear_fix">
                    <table class="grid" style="width: 900px;">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Дата - Время</th>
                                <th style="width: 150px;">Рабочее место</th>
                                <th style="">Оператор</th>
                                <th style="width: 200px;">Действие</th>
                                <th style="width: 150px;"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->dataResult as $row) { ?>
                            <tr>
                                <td><?php echo $row['datetime']; ?></td>
                                <td><?php echo $row['agentphone']; ?></td>
                                <td><?php echo html(QueueAgent::getOper($row['agentid']) ); ?></td>
                                <td><?php echo $row['action1']; ?></td>
                                <td><?php echo $row['action2']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
