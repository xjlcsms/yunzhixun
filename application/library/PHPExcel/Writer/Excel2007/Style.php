<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel\Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */


namespace PHPExcel;

/**
 * PHPExcel\Writer_Excel2007_Style
 *
 * @category   PHPExcel
 * @package    PHPExcel\Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class Writer_Excel2007_Style extends Writer_Excel2007_WriterPart
{
    /**
     * Write styles to XML format
     *
     * @param     PHPExcel\Workbook    $pPHPExcel
     * @return     string                 XML Output
     * @throws     PHPExcel\Writer_Exception
     */
    public function writeStyles(Workbook $pPHPExcel = null)
    {
        // Create XML writer
        $objWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new Shared_XMLWriter(Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new Shared_XMLWriter(Shared_XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $objWriter->startDocument('1.0','UTF-8','yes');

        // styleSheet
        $objWriter->startElement('styleSheet');
        $objWriter->writeAttribute('xml:space', 'preserve');
        $objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

            // numFmts
            $objWriter->startElement('numFmts');
            $objWriter->writeAttribute('count', $this->getParentWriter()->getNumFmtHashTable()->count());

                // numFmt
                for ($i = 0; $i < $this->getParentWriter()->getNumFmtHashTable()->count(); ++$i) {
                    $this->_writeNumFmt($objWriter, $this->getParentWriter()->getNumFmtHashTable()->getByIndex($i), $i);
                }

            $objWriter->endElement();

            // fonts
            $objWriter->startElement('fonts');
            $objWriter->writeAttribute('count', $this->getParentWriter()->getFontHashTable()->count());

                // font
                for ($i = 0; $i < $this->getParentWriter()->getFontHashTable()->count(); ++$i) {
                    $this->_writeFont($objWriter, $this->getParentWriter()->getFontHashTable()->getByIndex($i));
                }

            $objWriter->endElement();

            // fills
            $objWriter->startElement('fills');
            $objWriter->writeAttribute('count', $this->getParentWriter()->getFillHashTable()->count());

                // fill
                for ($i = 0; $i < $this->getParentWriter()->getFillHashTable()->count(); ++$i) {
                    $this->_writeFill($objWriter, $this->getParentWriter()->getFillHashTable()->getByIndex($i));
                }

            $objWriter->endElement();

            // borders
            $objWriter->startElement('borders');
            $objWriter->writeAttribute('count', $this->getParentWriter()->getBordersHashTable()->count());

                // border
                for ($i = 0; $i < $this->getParentWriter()->getBordersHashTable()->count(); ++$i) {
                    $this->_writeBorder($objWriter, $this->getParentWriter()->getBordersHashTable()->getByIndex($i));
                }

            $objWriter->endElement();

            // cellStyleXfs
            $objWriter->startElement('cellStyleXfs');
            $objWriter->writeAttribute('count', 1);

                // xf
                $objWriter->startElement('xf');
                    $objWriter->writeAttribute('numFmtId',     0);
                    $objWriter->writeAttribute('fontId',     0);
                    $objWriter->writeAttribute('fillId',     0);
                    $objWriter->writeAttribute('borderId',    0);
                $objWriter->endElement();

            $objWriter->endElement();

            // cellXfs
            $objWriter->startElement('cellXfs');
            $objWriter->writeAttribute('count', count($pPHPExcel->getCellXfCollection()));

                // xf
                foreach ($pPHPExcel->getCellXfCollection() as $cellXf) {
                    $this->_writeCellStyleXf($objWriter, $cellXf, $pPHPExcel);
                }

            $objWriter->endElement();

            // cellStyles
            $objWriter->startElement('cellStyles');
            $objWriter->writeAttribute('count', 1);

                // cellStyle
                $objWriter->startElement('cellStyle');
                    $objWriter->writeAttribute('name',         'Normal');
                    $objWriter->writeAttribute('xfId',         0);
                    $objWriter->writeAttribute('builtinId',    0);
                $objWriter->endElement();

            $objWriter->endElement();

            // dxfs
            $objWriter->startElement('dxfs');
            $objWriter->writeAttribute('count', $this->getParentWriter()->getStylesConditionalHashTable()->count());

                // dxf
                for ($i = 0; $i < $this->getParentWriter()->getStylesConditionalHashTable()->count(); ++$i) {
                    $this->_writeCellStyleDxf($objWriter, $this->getParentWriter()->getStylesConditionalHashTable()->getByIndex($i)->getStyle());
                }

            $objWriter->endElement();

            // tableStyles
            $objWriter->startElement('tableStyles');
            $objWriter->writeAttribute('defaultTableStyle', 'TableStyleMedium9');
            $objWriter->writeAttribute('defaultPivotStyle', 'PivotTableStyle1');
            $objWriter->endElement();

        $objWriter->endElement();

        // Return
        return $objWriter->getData();
    }

    /**
     * Write Fill
     *
     * @param     PHPExcel\Shared_XMLWriter     $objWriter         XML Writer
     * @param     PHPExcel\Style_Fill            $pFill            Fill style
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writeFill(Shared_XMLWriter $objWriter = null, Style_Fill $pFill = null)
    {
        // Check if this is a pattern type or gradient type
        if ($pFill->getFillType() === Style_Fill::FILL_GRADIENT_LINEAR ||
            $pFill->getFillType() === Style_Fill::FILL_GRADIENT_PATH) {
            // Gradient fill
            $this->_writeGradientFill($objWriter, $pFill);
        } elseif($pFill->getFillType() !== null) {
            // Pattern fill
            $this->_writePatternFill($objWriter, $pFill);
        }
    }

    /**
     * Write Gradient Fill
     *
     * @param     PHPExcel\Shared_XMLWriter     $objWriter         XML Writer
     * @param     PHPExcel\Style_Fill            $pFill            Fill style
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writeGradientFill(Shared_XMLWriter $objWriter = null, Style_Fill $pFill = null)
    {
        // fill
        $objWriter->startElement('fill');

            // gradientFill
            $objWriter->startElement('gradientFill');
                $objWriter->writeAttribute('type',         $pFill->getFillType());
                $objWriter->writeAttribute('degree',     $pFill->getRotation());

                // stop
                $objWriter->startElement('stop');
                $objWriter->writeAttribute('position', '0');

                    // color
                    $objWriter->startElement('color');
                    $objWriter->writeAttribute('rgb', $pFill->getStartColor()->getARGB());
                    $objWriter->endElement();

                $objWriter->endElement();

                // stop
                $objWriter->startElement('stop');
                $objWriter->writeAttribute('position', '1');

                    // color
                    $objWriter->startElement('color');
                    $objWriter->writeAttribute('rgb', $pFill->getEndColor()->getARGB());
                    $objWriter->endElement();

                $objWriter->endElement();

            $objWriter->endElement();

        $objWriter->endElement();
    }

    /**
     * Write Pattern Fill
     *
     * @param     PHPExcel\Shared_XMLWriter            $objWriter         XML Writer
     * @param     PHPExcel\Style_Fill                    $pFill            Fill style
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writePatternFill(Shared_XMLWriter $objWriter = null, Style_Fill $pFill = null)
    {
        // fill
        $objWriter->startElement('fill');

            // patternFill
            $objWriter->startElement('patternFill');
                $objWriter->writeAttribute('patternType', $pFill->getFillType());

                if ($pFill->getFillType() !== Style_Fill::FILL_NONE) {
                    // fgColor
                    if ($pFill->getStartColor()->getARGB()) {
                        $objWriter->startElement('fgColor');
                        $objWriter->writeAttribute('rgb', $pFill->getStartColor()->getARGB());
                        $objWriter->endElement();
                    }
                }
                if ($pFill->getFillType() !== Style_Fill::FILL_NONE) {
                    // bgColor
                    if ($pFill->getEndColor()->getARGB()) {
                        $objWriter->startElement('bgColor');
                        $objWriter->writeAttribute('rgb', $pFill->getEndColor()->getARGB());
                        $objWriter->endElement();
                    }
                }

            $objWriter->endElement();

        $objWriter->endElement();
    }

    /**
     * Write Font
     *
     * @param     PHPExcel\Shared_XMLWriter        $objWriter         XML Writer
     * @param     PHPExcel\Style_Font                $pFont            Font style
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writeFont(Shared_XMLWriter $objWriter = null, Style_Font $pFont = null)
    {
        // font
        $objWriter->startElement('font');
            //    Weird! The order of these elements actually makes a difference when opening Excel2007
            //        files in Excel2003 with the compatibility pack. It's not documented behaviour,
            //        and makes for a real WTF!

            // Bold. We explicitly write this element also when false (like MS Office Excel 2007 does
            // for conditional formatting). Otherwise it will apparently not be picked up in conditional
            // formatting style dialog
            if ($pFont->getBold() !== null) {
                $objWriter->startElement('b');
                    $objWriter->writeAttribute('val', $pFont->getBold() ? '1' : '0');
                $objWriter->endElement();
            }

            // Italic
            if ($pFont->getItalic() !== null) {
                $objWriter->startElement('i');
                    $objWriter->writeAttribute('val', $pFont->getItalic() ? '1' : '0');
                $objWriter->endElement();
            }

            // Strikethrough
            if ($pFont->getStrikethrough() !== null) {
                $objWriter->startElement('strike');
                $objWriter->writeAttribute('val', $pFont->getStrikethrough() ? '1' : '0');
                $objWriter->endElement();
            }

            // Underline
            if ($pFont->getUnderline() !== null) {
                $objWriter->startElement('u');
                $objWriter->writeAttribute('val', $pFont->getUnderline());
                $objWriter->endElement();
            }

            // Superscript / subscript
            if ($pFont->getSuperScript() === TRUE || $pFont->getSubScript() === TRUE) {
                $objWriter->startElement('vertAlign');
                if ($pFont->getSuperScript() === TRUE) {
                    $objWriter->writeAttribute('val', 'superscript');
                } else if ($pFont->getSubScript() === TRUE) {
                    $objWriter->writeAttribute('val', 'subscript');
                }
                $objWriter->endElement();
            }

            // Size
            if ($pFont->getSize() !== null) {
                $objWriter->startElement('sz');
                    $objWriter->writeAttribute('val', $pFont->getSize());
                $objWriter->endElement();
            }

            // Foreground color
            if ($pFont->getColor()->getARGB() !== null) {
                $objWriter->startElement('color');
                $objWriter->writeAttribute('rgb', $pFont->getColor()->getARGB());
                $objWriter->endElement();
            }

            // Name
            if ($pFont->getName() !== null) {
                $objWriter->startElement('name');
                    $objWriter->writeAttribute('val', $pFont->getName());
                $objWriter->endElement();
            }

        $objWriter->endElement();
    }

    /**
     * Write Border
     *
     * @param     PHPExcel\Shared_XMLWriter            $objWriter         XML Writer
     * @param     PHPExcel\Style_Borders                $pBorders        Borders style
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writeBorder(Shared_XMLWriter $objWriter = null, Style_Borders $pBorders = null)
    {
        // Write border
        $objWriter->startElement('border');
            // Diagonal?
            switch ($pBorders->getDiagonalDirection()) {
                case Style_Borders::DIAGONAL_UP:
                    $objWriter->writeAttribute('diagonalUp',     'true');
                    $objWriter->writeAttribute('diagonalDown',     'false');
                    break;
                case Style_Borders::DIAGONAL_DOWN:
                    $objWriter->writeAttribute('diagonalUp',     'false');
                    $objWriter->writeAttribute('diagonalDown',     'true');
                    break;
                case Style_Borders::DIAGONAL_BOTH:
                    $objWriter->writeAttribute('diagonalUp',     'true');
                    $objWriter->writeAttribute('diagonalDown',     'true');
                    break;
            }

            // BorderPr
            $this->_writeBorderPr($objWriter, 'left',        $pBorders->getLeft());
            $this->_writeBorderPr($objWriter, 'right',        $pBorders->getRight());
            $this->_writeBorderPr($objWriter, 'top',        $pBorders->getTop());
            $this->_writeBorderPr($objWriter, 'bottom',        $pBorders->getBottom());
            $this->_writeBorderPr($objWriter, 'diagonal',    $pBorders->getDiagonal());
        $objWriter->endElement();
    }

    /**
     * Write Cell Style Xf
     *
     * @param     PHPExcel\Shared_XMLWriter            $objWriter         XML Writer
     * @param     PHPExcel\Style                        $pStyle            Style
     * @param     PHPExcel\Workbook                    $pPHPExcel        Workbook
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writeCellStyleXf(Shared_XMLWriter $objWriter = null, Style $pStyle = null, Workbook $pPHPExcel = null)
    {
        // xf
        $objWriter->startElement('xf');
            $objWriter->writeAttribute('xfId', 0);
            $objWriter->writeAttribute('fontId',             (int)$this->getParentWriter()->getFontHashTable()->getIndexForHashCode($pStyle->getFont()->getHashCode()));

            if ($pStyle->getQuotePrefix()) {
                $objWriter->writeAttribute('quotePrefix', 	    1);
            }
            if ($pStyle->getNumberFormat()->getBuiltInFormatCode() === false) {
                $objWriter->writeAttribute('numFmtId',             (int)($this->getParentWriter()->getNumFmtHashTable()->getIndexForHashCode($pStyle->getNumberFormat()->getHashCode()) + 164)   );
            } else {
                $objWriter->writeAttribute('numFmtId',             (int)$pStyle->getNumberFormat()->getBuiltInFormatCode());
            }

            $objWriter->writeAttribute('fillId',             (int)$this->getParentWriter()->getFillHashTable()->getIndexForHashCode($pStyle->getFill()->getHashCode()));
            $objWriter->writeAttribute('borderId',             (int)$this->getParentWriter()->getBordersHashTable()->getIndexForHashCode($pStyle->getBorders()->getHashCode()));

            // Apply styles?
            $objWriter->writeAttribute('applyFont',         ($pPHPExcel->getDefaultStyle()->getFont()->getHashCode() != $pStyle->getFont()->getHashCode()) ? '1' : '0');
            $objWriter->writeAttribute('applyNumberFormat', ($pPHPExcel->getDefaultStyle()->getNumberFormat()->getHashCode() != $pStyle->getNumberFormat()->getHashCode()) ? '1' : '0');
            $objWriter->writeAttribute('applyFill',         ($pPHPExcel->getDefaultStyle()->getFill()->getHashCode() != $pStyle->getFill()->getHashCode()) ? '1' : '0');
            $objWriter->writeAttribute('applyBorder',         ($pPHPExcel->getDefaultStyle()->getBorders()->getHashCode() != $pStyle->getBorders()->getHashCode()) ? '1' : '0');
            $objWriter->writeAttribute('applyAlignment',    ($pPHPExcel->getDefaultStyle()->getAlignment()->getHashCode() != $pStyle->getAlignment()->getHashCode()) ? '1' : '0');
            if ($pStyle->getProtection()->getLocked() != Style_Protection::PROTECTION_INHERIT || $pStyle->getProtection()->getHidden() != Style_Protection::PROTECTION_INHERIT) {
                $objWriter->writeAttribute('applyProtection', 'true');
            }

            // alignment
            $objWriter->startElement('alignment');
                $objWriter->writeAttribute('horizontal',     $pStyle->getAlignment()->getHorizontal());
                $objWriter->writeAttribute('vertical',         $pStyle->getAlignment()->getVertical());

                $textRotation = 0;
                if ($pStyle->getAlignment()->getTextRotation() >= 0) {
                    $textRotation = $pStyle->getAlignment()->getTextRotation();
                } else if ($pStyle->getAlignment()->getTextRotation() < 0) {
                    $textRotation = 90 - $pStyle->getAlignment()->getTextRotation();
                }
                $objWriter->writeAttribute('textRotation',     $textRotation);

                $objWriter->writeAttribute('wrapText',         ($pStyle->getAlignment()->getWrapText() ? 'true' : 'false'));
                $objWriter->writeAttribute('shrinkToFit',     ($pStyle->getAlignment()->getShrinkToFit() ? 'true' : 'false'));

                if ($pStyle->getAlignment()->getIndent() > 0) {
                    $objWriter->writeAttribute('indent',     $pStyle->getAlignment()->getIndent());
                }
            $objWriter->endElement();

            // protection
            if ($pStyle->getProtection()->getLocked() != Style_Protection::PROTECTION_INHERIT || $pStyle->getProtection()->getHidden() != Style_Protection::PROTECTION_INHERIT) {
                $objWriter->startElement('protection');
                    if ($pStyle->getProtection()->getLocked() != Style_Protection::PROTECTION_INHERIT) {
                        $objWriter->writeAttribute('locked',         ($pStyle->getProtection()->getLocked() == Style_Protection::PROTECTION_PROTECTED ? 'true' : 'false'));
                    }
                    if ($pStyle->getProtection()->getHidden() != Style_Protection::PROTECTION_INHERIT) {
                        $objWriter->writeAttribute('hidden',         ($pStyle->getProtection()->getHidden() == Style_Protection::PROTECTION_PROTECTED ? 'true' : 'false'));
                    }
                $objWriter->endElement();
            }

        $objWriter->endElement();
    }

    /**
     * Write Cell Style Dxf
     *
     * @param     PHPExcel\Shared_XMLWriter         $objWriter         XML Writer
     * @param     PHPExcel\Style                    $pStyle            Style
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writeCellStyleDxf(Shared_XMLWriter $objWriter = null, Style $pStyle = null)
    {
        // dxf
        $objWriter->startElement('dxf');

            // font
            $this->_writeFont($objWriter, $pStyle->getFont());

            // numFmt
            $this->_writeNumFmt($objWriter, $pStyle->getNumberFormat());

            // fill
            $this->_writeFill($objWriter, $pStyle->getFill());

            // alignment
            $objWriter->startElement('alignment');
                if ($pStyle->getAlignment()->getHorizontal() !== null) {
                    $objWriter->writeAttribute('horizontal', $pStyle->getAlignment()->getHorizontal());
                }
                if ($pStyle->getAlignment()->getVertical() !== null) {
                    $objWriter->writeAttribute('vertical', $pStyle->getAlignment()->getVertical());
                }

                if ($pStyle->getAlignment()->getTextRotation() !== null) {
                    $textRotation = 0;
                    if ($pStyle->getAlignment()->getTextRotation() >= 0) {
                        $textRotation = $pStyle->getAlignment()->getTextRotation();
                    } else if ($pStyle->getAlignment()->getTextRotation() < 0) {
                        $textRotation = 90 - $pStyle->getAlignment()->getTextRotation();
                    }
                    $objWriter->writeAttribute('textRotation',     $textRotation);
                }
            $objWriter->endElement();

            // border
            $this->_writeBorder($objWriter, $pStyle->getBorders());

            // protection
            if (($pStyle->getProtection()->getLocked() !== null) ||
                ($pStyle->getProtection()->getHidden() !== null)) {
                if ($pStyle->getProtection()->getLocked() !== Style_Protection::PROTECTION_INHERIT ||
                    $pStyle->getProtection()->getHidden() !== Style_Protection::PROTECTION_INHERIT) {
                    $objWriter->startElement('protection');
                        if (($pStyle->getProtection()->getLocked() !== null) &&
                            ($pStyle->getProtection()->getLocked() !== Style_Protection::PROTECTION_INHERIT)) {
                            $objWriter->writeAttribute('locked', ($pStyle->getProtection()->getLocked() == Style_Protection::PROTECTION_PROTECTED ? 'true' : 'false'));
                        }
                        if (($pStyle->getProtection()->getHidden() !== null) &&
                            ($pStyle->getProtection()->getHidden() !== Style_Protection::PROTECTION_INHERIT)) {
                            $objWriter->writeAttribute('hidden', ($pStyle->getProtection()->getHidden() == Style_Protection::PROTECTION_PROTECTED ? 'true' : 'false'));
                        }
                    $objWriter->endElement();
                }
            }

        $objWriter->endElement();
    }

    /**
     * Write BorderPr
     *
     * @param     PHPExcel\Shared_XMLWriter        $objWriter         XML Writer
     * @param     string                            $pName            Element name
     * @param     PHPExcel\Style_Border            $pBorder        Border style
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writeBorderPr(Shared_XMLWriter $objWriter = null, $pName = 'left', Style_Border $pBorder = null)
    {
        // Write BorderPr
        if ($pBorder->getBorderStyle() != Style_Border::BORDER_NONE) {
            $objWriter->startElement($pName);
            $objWriter->writeAttribute('style',     $pBorder->getBorderStyle());

                // color
                $objWriter->startElement('color');
                $objWriter->writeAttribute('rgb',     $pBorder->getColor()->getARGB());
                $objWriter->endElement();

            $objWriter->endElement();
        }
    }

    /**
     * Write NumberFormat
     *
     * @param     PHPExcel\Shared_XMLWriter            $objWriter         XML Writer
     * @param     PHPExcel\Style_NumberFormat            $pNumberFormat    Number Format
     * @param     int                                    $pId            Number Format identifier
     * @throws     PHPExcel\Writer_Exception
     */
    private function _writeNumFmt(Shared_XMLWriter $objWriter = null, Style_NumberFormat $pNumberFormat = null, $pId = 0)
    {
        // Translate formatcode
        $formatCode = $pNumberFormat->getFormatCode();

        // numFmt
        if ($formatCode !== null) {
            $objWriter->startElement('numFmt');
                $objWriter->writeAttribute('numFmtId', ($pId + 164));
                $objWriter->writeAttribute('formatCode', $formatCode);
            $objWriter->endElement();
        }
    }

    /**
     * Get an array of all styles
     *
     * @param     PHPExcel                $pPHPExcel
     * @return     PHPExcel\Style[]        All styles in PHPExcel
     * @throws     PHPExcel\Writer_Exception
     */
    public function allStyles(Workbook $pPHPExcel = null)
    {
        $aStyles = $pPHPExcel->getCellXfCollection();

        return $aStyles;
    }

    /**
     * Get an array of all conditional styles
     *
     * @param     PHPExcel                            $pPHPExcel
     * @return     PHPExcel\Style_Conditional[]        All conditional styles in PHPExcel
     * @throws     PHPExcel\Writer_Exception
     */
    public function allConditionalStyles(Workbook $pPHPExcel = null)
    {
        // Get an array of all styles
        $aStyles        = array();

        $sheetCount = $pPHPExcel->getSheetCount();
        for ($i = 0; $i < $sheetCount; ++$i) {
            foreach ($pPHPExcel->getSheet($i)->getConditionalStylesCollection() as $conditionalStyles) {
                foreach ($conditionalStyles as $conditionalStyle) {
                    $aStyles[] = $conditionalStyle;
                }
            }
        }

        return $aStyles;
    }

    /**
     * Get an array of all fills
     *
     * @param     PHPExcel                        $pPHPExcel
     * @return     PHPExcel\Style_Fill[]        All fills in PHPExcel
     * @throws     PHPExcel\Writer_Exception
     */
    public function allFills(Workbook $pPHPExcel = null)
    {
        // Get an array of unique fills
        $aFills     = array();

        // Two first fills are predefined
        $fill0 = new Style_Fill();
        $fill0->setFillType(Style_Fill::FILL_NONE);
        $aFills[] = $fill0;

        $fill1 = new Style_Fill();
        $fill1->setFillType(Style_Fill::FILL_PATTERN_GRAY125);
        $aFills[] = $fill1;
        // The remaining fills
        $aStyles     = $this->allStyles($pPHPExcel);
        foreach ($aStyles as $style) {
            if (!array_key_exists($style->getFill()->getHashCode(), $aFills)) {
                $aFills[ $style->getFill()->getHashCode() ] = $style->getFill();
            }
        }

        return $aFills;
    }

    /**
     * Get an array of all fonts
     *
     * @param     PHPExcel                        $pPHPExcel
     * @return     PHPExcel\Style_Font[]        All fonts in PHPExcel
     * @throws     PHPExcel\Writer_Exception
     */
    public function allFonts(Workbook $pPHPExcel = null)
    {
        // Get an array of unique fonts
        $aFonts     = array();
        $aStyles     = $this->allStyles($pPHPExcel);

        foreach ($aStyles as $style) {
            if (!array_key_exists($style->getFont()->getHashCode(), $aFonts)) {
                $aFonts[ $style->getFont()->getHashCode() ] = $style->getFont();
            }
        }

        return $aFonts;
    }

    /**
     * Get an array of all borders
     *
     * @param     PHPExcel                        $pPHPExcel
     * @return     PHPExcel\Style_Borders[]        All borders in PHPExcel
     * @throws     PHPExcel\Writer_Exception
     */
    public function allBorders(Workbook $pPHPExcel = null)
    {
        // Get an array of unique borders
        $aBorders     = array();
        $aStyles     = $this->allStyles($pPHPExcel);

        foreach ($aStyles as $style) {
            if (!array_key_exists($style->getBorders()->getHashCode(), $aBorders)) {
                $aBorders[ $style->getBorders()->getHashCode() ] = $style->getBorders();
            }
        }

        return $aBorders;
    }

    /**
     * Get an array of all number formats
     *
     * @param     PHPExcel                                $pPHPExcel
     * @return     PHPExcel\Style_NumberFormat[]        All number formats in PHPExcel
     * @throws     PHPExcel\Writer_Exception
     */
    public function allNumberFormats(Workbook $pPHPExcel = null)
    {
        // Get an array of unique number formats
        $aNumFmts     = array();
        $aStyles     = $this->allStyles($pPHPExcel);

        foreach ($aStyles as $style) {
            if ($style->getNumberFormat()->getBuiltInFormatCode() === false && !array_key_exists($style->getNumberFormat()->getHashCode(), $aNumFmts)) {
                $aNumFmts[ $style->getNumberFormat()->getHashCode() ] = $style->getNumberFormat();
            }
        }

        return $aNumFmts;
    }
}
